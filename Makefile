PROJECT_NAME := $(shell grep '^COMPOSE_PROJECT_NAME=' .env | cut -d'=' -f2)

ifeq ($(PROJECT_NAME),)
  PROJECT_NAME := $(shell read -p "Enter the project name: " PROJECT_NAME && echo $$PROJECT_NAME)
endif

PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

install: setup-local-environment setup-testing-environment update-certificates update-hosts

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
	@if [ ! -d "./certs" ]; then mkdir ./certs; fi
	@rm -rf ./certs/*
	@mkcert -key-file ./certs/${PROJECT_NAME_SLUG}.test.key -cert-file ./certs/${PROJECT_NAME_SLUG}.test.crt "*.${PROJECT_NAME_SLUG}.test" ${PROJECT_NAME_SLUG}.test 0.0.0.0 127.0.0.1 > /dev/null 2>&1
	@echo "✅ SSL certificates generated."

.PHONY: setup_testing_environment
update-hosts:
	@echo "📝 Adding ${PROJECT_NAME_SLUG}.test to /etc/hosts if needed..."
	@if ! grep -q "${PROJECT_NAME_SLUG}.test" /etc/hosts; then \
		echo "127.0.0.1 ${PROJECT_NAME_SLUG}.test" | sudo tee -a /etc/hosts > /dev/null; \
		echo "✅ Hosts file updated."; \
	else \
		echo "ℹ️ Entry already exists."; \
	fi

.PHONY: build
build:
	@docker compose build

.PHONY: rebuild
rebuild:
	@docker compose build && @docker compose up -d

.PHONY: up
up:
	@docker compose up -d

.PHONY: down
down:
	@docker compose down

.PHONY: restart
restart: down up

.PHONE: purge
purge:
	docker compose down --remove-orphans --volumes
	docker network prune --force
	docker volume prune --force
	docker image prune --force

.PHONY: composer
composer:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" composer $(if $(COMMAND),$(COMMAND),)

.PHONY: artisan
artisan:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" php artisan $(if $(COMMAND),$(COMMAND),)

.PHONY: phpstan
phpstan:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" vendor/bin/phpstan $(if $(COMMAND),$(COMMAND),analyse)

.PHONY: pint
pint:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" ./vendor/bin/pint $(if $(COMMAND),$(COMMAND),)

.PHONY: pnpm
pnpm:
	@docker exec -it "$(PROJECT_NAME_SLUG)-hybridly" pnpm $(if $(COMMAND),$(COMMAND), --version)

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
