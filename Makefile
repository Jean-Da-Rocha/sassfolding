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

.PHONY: setup-dns
setup-dns:
	@echo "🌐 Configuring system DNS to route *.test to 127.0.0.1..."
ifeq ($(shell uname),Darwin)
	@sudo networksetup -setdnsservers Wi-Fi 127.0.0.1
	@sudo mkdir -p /etc/resolver
	@echo "nameserver 127.0.0.1" | sudo tee /etc/resolver/test > /dev/null
	@echo "✅ MacOS DNS setup complete for *.test."
else
	@if ! grep -q '^nameserver 127.0.0.1$$' /etc/resolv.conf; then \
		echo "🔧 Adding 127.0.0.1 to /etc/resolv.conf..."; \
		sudo cp /etc/resolv.conf /etc/resolv.conf.bak; \
		echo "nameserver 127.0.0.1" | sudo tee /etc/resolv.conf.new > /dev/null; \
		cat /etc/resolv.conf.bak | grep -v '^nameserver 127.0.0.1$$' | sudo tee -a /etc/resolv.conf.new > /dev/null; \
		sudo mv /etc/resolv.conf.new /etc/resolv.conf; \
		echo "✅ Updated /etc/resolv.conf with 127.0.0.1 at the top."; \
	else \
		echo "✅ 127.0.0.1 is already in /etc/resolv.conf. Skipping."; \
	fi

	@echo "🔧 Disabling systemd-resolved DNSStubListener to avoid conflicts..."
	@sudo sed -i 's/^#DNSStubListener=yes/DNSStubListener=no/' /etc/systemd/resolved.conf || true
	@sudo systemctl restart systemd-resolved
	@echo "✅ DNSStubListener disabled and systemd-resolved restarted."
endif

.PHONY: restore-dns
restore-dns:
	@echo "🛠 Restoring system DNS with systemd-resolved..."

	@echo "🔁 Re-enabling DNSStubListener..."
	@sudo sed -i 's/^DNSStubListener=no/#DNSStubListener=yes/' /etc/systemd/resolved.conf || true

	@echo "🔗 Restoring /etc/resolv.conf symlink..."
	@if [ ! -L /etc/resolv.conf ]; then \
		sudo rm -f /etc/resolv.conf; \
		sudo ln -s /run/systemd/resolve/stub-resolv.conf /etc/resolv.conf; \
	fi

	@sudo systemctl restart docker
	@sleep 2
	@sudo systemctl restart systemd-resolved

	@echo "✅ DNS restored with systemd-resolved (nameserver 127.0.0.53)"

.PHONY: build
build:
	@make restore-dns
	@docker compose build

.PHONY: rebuild
rebuild:
	@make restore-dns
	@docker compose down -v
	@docker compose build
	@make setup-dns
	@docker compose up -d

.PHONY: up
start:
	@docker compose up -d

.PHONY: down
stop:
	@docker compose down

.PHONY: restart
restart: stop start

.PHONE: purge
purge:
	docker compose down --remove-orphans --volumes
	docker network prune --force
	docker volume prune --force
	docker image prune --force

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
