DNS_DOMAIN=test
DNSMASQ_IP_ADDRESS=172.18.0.10

PROJECT_NAME := $(shell grep '^COMPOSE_PROJECT_NAME=' .env | cut -d'=' -f2)

ifeq ($(PROJECT_NAME),)
  PROJECT_NAME := $(shell read -p "Enter the project name: " PROJECT_NAME && echo $$PROJECT_NAME)
endif

PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

install: setup-local-environment setup-testing-environment update-certificates

.PHONY: setup-local-environment
setup-local-environment:
	@echo "🔧 Setting up the local environment..."
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.example > .env
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env
	@echo "✅ Local environment ready."

.PHONY: setup_testing_environment
setup-testing-environment:
	@echo "🧪 Setting up the testing environment..."
	@if [ ! -f .env.testing ]; then cp .env.testing.example .env.testing; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.testing.example > .env.testing
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env.testing
	@echo "✅ Testing environment ready."

.PHONY: update-certificates
update-certificates:
	@echo "🔐 Updating SSL certificates for ${PROJECT_NAME_SLUG}.test..."
	@mkcert \
		-cert-file ./docker/traefik/certs/${PROJECT_NAME_SLUG}.cert \
		-key-file ./docker/traefik/certs/${PROJECT_NAME_SLUG}.key \
		"*.${PROJECT_NAME_SLUG}.test" \
		"${PROJECT_NAME_SLUG}.test" \
		127.0.0.1 0.0.0.0 > /dev/null 2>&1
	@echo "✅ SSL certificates generated."
	@echo "🔍 Copying mkcert root CA..."
	@cp "$$(mkcert -CAROOT)/rootCA.pem" ./docker/ssl/rootCA.pem
	@echo "✅ mkcert root CA copied."

setup-dns:
	@echo "🌐 Setting up DNS for *.$(DNS_DOMAIN)..."
ifeq ($(shell uname),Darwin)
	@echo "🔧 macOS: Configuring DNS resolver for *.$(DNS_DOMAIN)..."
	@if [ ! -f /etc/resolver/$(DNS_DOMAIN) ]; then \
		echo "🔧 Adding DNS resolver configuration for *.$(DNS_DOMAIN)..."; \
		sudo mkdir -p /etc/resolver; \
		echo "nameserver $(DNSMASQ_IP_ADDRESS)" | sudo tee /etc/resolver/$(DNS_DOMAIN) > /dev/null; \
		echo "✅ macOS DNS resolver added for *.$(DNS_DOMAIN)"; \
	else \
		echo "⚠️ DNS resolver for $(DNS_DOMAIN) already exists. Skipping..."; \
	fi
else
	@echo "🔧 Linux: Configuring DNS resolver for *.$(DNS_DOMAIN)..."
	@if [ ! -d /etc/systemd/resolved.conf.d ]; then \
		sudo mkdir -p /etc/systemd/resolved.conf.d; \
		echo "🛠️ Created /etc/systemd/resolved.conf.d directory."; \
	fi
	@echo "[Resolve]" | sudo tee /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "DNS=$(DNSMASQ_IP_ADDRESS)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "Domains=$(DNS_DOMAIN)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@if systemctl is-active --quiet systemd-resolved; then \
		sudo systemctl restart systemd-resolved; \
		echo "✅ Linux systemd-resolved DNS config added for *.$(DNS_DOMAIN)"; \
	else \
		echo "⚠️ systemd-resolved is not running. Please start it manually."; \
	fi
endif

restore-dns:
	@echo "🧹 Restoring default DNS..."
ifeq ($(shell uname),Darwin)
	@echo "🔧 macOS: Restoring default DNS resolver for *.$(DNS_DOMAIN)..."
	@sudo rm -f /etc/resolver/$(DNS_DOMAIN)
	@echo "✅ Removed macOS DNS resolver for *.$(DNS_DOMAIN)"
else
	@echo "🔧 Linux: Restoring default DNS resolver for *.$(DNS_DOMAIN)..."
	@if [ -f /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf ]; then \
		sudo rm /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf; \
		sudo systemctl restart systemd-resolved; \
		echo "✅ Removed Linux systemd-resolved config for *.$(DNS_DOMAIN)"; \
	else \
		echo "ℹ️ No systemd-resolved DNS override to remove."; \
	fi
endif

.PHONY: build
build:
	@make restore-dns
	@docker compose build

.PHONY: rebuild
rebuild:
	@docker compose down -v
	@make restore-dns
	@docker compose build
	@make setup-dns
	@docker compose up -d

.PHONY: up
start:
	@docker compose up -d

.PHONY: down
stop:
	@docker compose down --remove-orphans

.PHONY: restart
restart: stop start

.PHONY: purge
purge:
	@docker compose down --remove-orphans --volumes
	@docker network prune --force
	@docker volume prune --force
	@docker image prune --force

.PHONY: destroy
destroy:
	docker compose down --remove-orphans --volumes
	docker system prune -a -f --volumes

.PHONY: composer
composer:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" bash -c "composer $(cmd)"

.PHONY: back
back:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly"

.PHONY: artisan
artisan:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" bash -c "php artisan $(cmd)"

.PHONY: artisan
tinker:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" php artisan tinker

.PHONY: phpstan
phpstan:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" vendor/bin/phpstan analyze

.PHONY: pint
pint:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" ./vendor/bin/pint

.PHONY: pnpm
pnpm:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" bash -c "pnpm $(or $(cmd), --version)"

.PHONY: vue-tsc
vue-tsc:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" pnpm run vue-tsc

.PHONY: eslint
eslint:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" pnpm run lint:fix

.PHONY: taze
taze:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" pnpx taze

.PHONY: taze-major
taze-major:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" pnpx taze major
