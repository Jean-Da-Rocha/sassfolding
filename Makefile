# Empty by default. Set a value if you don't want to use the working directory as project name.
OVERRIDE_PROJECT_NAME ?=

CYAN   := \033[0;36m
GREEN  := \033[0;32m
RED    := \033[0;31m
RESET  := \033[0m
YELLOW := \033[0;33m

ifndef VERBOSE
	MAKEFLAGS += --no-print-directory
endif

DNS_DOMAIN=test
DNSMASQ_IP_ADDRESS=172.18.0.10

ifneq ("$(wildcard .env)","")
  PROJECT_NAME := $(shell grep '^COMPOSE_PROJECT_NAME=' .env | cut -d'=' -f2)
endif

ifeq ($(PROJECT_NAME),)
  PROJECT_NAME := $(shell basename $(PWD))
endif

PROJECT_NAME := $(if $(OVERRIDE_PROJECT_NAME), $(OVERRIDE_PROJECT_NAME), $(PROJECT_NAME))

PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

DOCKER ?= @docker
DOCKER_COMPOSE ?= $(DOCKER) compose
HORIZON_EXEC ?= $(DOCKER_COMPOSE) exec -it horizon
HYBRIDLY_EXEC ?= $(DOCKER_COMPOSE) exec -it hybridly
HYBRIDLY_RUNNER ?= $(DOCKER_COMPOSE) run --rm --no-deps hybridly

.PHONY: artisan
artisan: ## Run artisan commands using make artisan cmd="" syntax.
	$(HYBRIDLY_EXEC) php artisan $(cmd)

.PHONY: build
build: ## Build the docker images for the project, optionally without cache using 'make build keep-cache=0' syntax.
	$(DOCKER_COMPOSE) build $(if $(filter 0, $(keep-cache)), --no-cache)

.PHONY: composer
composer: ## Run composer commands using the 'make composer cmd=""' syntax.
	$(HYBRIDLY_EXEC) composer $(cmd)

.PHONY: destroy
destroy: ## Tear down the project, removing volumes and pruning Docker system.
	$(DOCKER) system prune --all --force --volumes

.PHONY: eslint
eslint: ## Run ESLint with automatic fixing.
	$(HYBRIDLY_EXEC) pnpm run lint:fix

.PHONY: help
help:
	@echo 'Available make commands:'
	@grep -E '^[a-zA-Z_0-9%-]+:.*?## .*$$' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "    -${CYAN}%-25s${RESET}: %s\n", $$1, $$2}'
	@echo

.PHONY: horizon-continue
horizon-continue: ## Continue a paused Horizon queue.
	$(HORIZON_EXEC) php artisan horizon:continue

.PHONY: horizon-pause
horizon-pause: ## Pause the Horizon queue.
	$(HORIZON_EXEC) php artisan horizon:pause

.PHONY: horizon-start
horizon-start: ## Start the Horizon queue.
	$(HORIZON_EXEC) php artisan horizon:start

.PHONY: horizon-terminate
horizon-terminate: ## Terminate the Horizon queue.
	$(HORIZON_EXEC) php artisan horizon:terminate

.PHONY: install
install: ## Install dependencies and set up the local and testing environments.
	@$(MAKE) restore-dns
	@$(MAKE) setup-local-environment
	@$(MAKE) setup-testing-environment
	@$(MAKE) build keep-cache=0
	@$(MAKE) install-all-deps
	@echo "$(CYAN)[INFO]: Generating APP_KEY for local and testing environments...$(RESET)"
	@$(HYBRIDLY_RUNNER) php artisan key:generate && php artisan key:generate --env=testing
	@$(MAKE) update-certificates
	@$(MAKE) setup-dns
	@$(MAKE) restart

.PHONY: install-all-deps
install-all-deps: ## Install both composer and pnpm dependencies.
	@$(MAKE) install-composer-deps
	@$(MAKE) install-pnpm-deps

.PHONY: install-composer-deps
install-composer-deps: ## Install composer dependencies.
	$(HYBRIDLY_RUNNER) composer install --prefer-dist --no-interaction --no-progress

.PHONY: install-pnpm-deps
install-pnpm-deps: ## Install pnpm dependencies.
	$(HYBRIDLY_RUNNER) pnpm install --frozen-lockfile --force

.PHONY: phpstan
phpstan: ## Run static analysis with PHPStan.
	$(HYBRIDLY_EXEC) vendor/bin/phpstan analyze

.PHONY: pint
pint: ## Run Laravel Pint to fix coding style issues.
	$(HYBRIDLY_EXEC) vendor/bin/pint

.PHONY: pnpm
pnpm: ## Run pnpm commands using the 'make pnpm cmd=""' syntax.
	$(HYBRIDLY_EXEC) pnpm $(or $(cmd), --version)

.PHONY: purge
purge: ## Purge all Docker containers, images, networks, and volumes.
	@$(MAKE) stop keep-volumes=0
	$(DOCKER) network prune --force
	$(DOCKER) volume prune --force
	$(DOCKER) image prune --force

.PHONY: rebuild
rebuild: ## Rebuild and restart docker containers for this project, optionally removing volumes and not using cache using 'make rebuild keep-cache=0 keep-volumes=0' syntax.
	@$(MAKE) stop $(if $(keep-volumes), keep-volumes=$(keep-volumes))
	@$(MAKE) restore-dns
	@$(MAKE) build $(if $(keep-cache), keep-cache=$(keep-cache))
	@$(MAKE) setup-dns
	@$(MAKE) start

.PHONY: restart
restart: ## Restart the project by stopping and starting all containers.
	@$(MAKE) stop
	@$(MAKE) start

.PHONY: restore-dns
restore-dns: ## Restore the default DNS settings.
	@echo "$(CYAN)[INFO]: Restoring default DNS...$(RESET)"
ifeq ($(shell uname),Darwin)
	@echo "$(CYAN)[INFO]: macOS: Restoring default DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ -f /etc/resolver/$(DNS_DOMAIN) ]; then \
		sudo rm -f /etc/resolver/$(DNS_DOMAIN); \
		echo "$(GREEN)[SUCCESS]: Removed macOS DNS resolver for *.$(DNS_DOMAIN).$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: No macOS DNS resolver for *.$(DNS_DOMAIN) to remove.$(RESET)"; \
	fi
else
	@echo "$(CYAN)[INFO]: Linux: Restoring default DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ -f /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf ]; then \
		sudo rm /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf; \
		sudo systemctl restart systemd-resolved; \
		echo "$(GREEN)[SUCCESS]: Removed Linux systemd-resolved config for *.$(DNS_DOMAIN).$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: No systemd-resolved DNS override to remove.$(RESET)"; \
	fi
endif

.PHONY: setup-dns
setup-dns: ## Set up DNS resolver for the provided top level domain (TLD).
	@echo "$(CYAN)[INFO]: Setting up DNS for *.$(DNS_DOMAIN)...$(RESET)"
ifeq ($(shell uname),Darwin)
	@echo "$(CYAN)[INFO]: macOS: Configuring DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ ! -f /etc/resolver/$(DNS_DOMAIN) ]; then \
		echo "$(CYAN)[INFO]: Adding DNS resolver configuration...$(RESET)"; \
		sudo mkdir -p /etc/resolver; \
		echo "nameserver $(DNSMASQ_IP_ADDRESS)" | sudo tee /etc/resolver/$(DNS_DOMAIN) > /dev/null; \
		echo "$(GREEN)[SUCCESS]: macOS DNS resolver added for *.$(DNS_DOMAIN)$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: DNS resolver for $(DNS_DOMAIN) already exists. Skipping...$(RESET)"; \
	fi
else
	@echo "$(CYAN)[INFO]: Linux: Configuring DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ ! -d /etc/systemd/resolved.conf.d ]; then \
		sudo mkdir -p /etc/systemd/resolved.conf.d; \
		echo "$(CYAN)[INFO]: Created /etc/systemd/resolved.conf.d directory.$(RESET)"; \
	fi
	@echo "[Resolve]" | sudo tee /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "DNS=$(DNSMASQ_IP_ADDRESS)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "Domains=$(DNS_DOMAIN)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@if systemctl is-active --quiet systemd-resolved; then \
		sudo systemctl restart systemd-resolved; \
		echo "$(GREEN)[SUCCESS]: Linux systemd-resolved DNS config added for *.$(DNS_DOMAIN)$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: systemd-resolved is not running. Please start it manually.$(RESET)"; \
	fi
endif

define setup_environment
	@echo "$(CYAN)[INFO]: Setting up the $(1) environment...$(RESET)"
	@if [ ! -f $(2) ]; then cp $(2).example $(2); fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < $(2).example > $(2)
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" $(2)
	@echo "$(GREEN)[SUCCESS]: $(1) environment ready.$(RESET)"
endef

.PHONY: setup-local-environment
setup-local-environment: ## Set up the local environment from the .env.example file.
	$(call setup_environment,local,.env)

.PHONY: setup-testing-environment
setup-testing-environment: ## Set up the testing environment from the .env.testing.example file.
	$(call setup_environment,testing,.env.testing)

.PHONY: start
start: ## Start the Docker containers for the project.
	$(DOCKER_COMPOSE) up --detach --remove-orphans

.PHONY: stop
stop: ## Stop the Docker containers for the project, optionally removing volumes using 'make stop keep-volumes=0' syntax.
	$(DOCKER_COMPOSE) down --remove-orphans $(if $(filter 0, $(keep-volumes)), --volumes)

.PHONY: taze
taze: ## Run pnpx taze to check for outdated minor dependencies.
	$(HYBRIDLY_EXEC) pnpx taze

.PHONY: taze-major
taze-major: ## Run pnpx taze to check major version updates only.
	$(HYBRIDLY_EXEC) pnpx taze major

.PHONY: taze-major-write
taze-major-write: ## Write major version updates to package.json and install them.
	$(HYBRIDLY_EXEC) pnpx taze major -w && $(MAKE) pnpm cmd="install"

.PHONY: taze-write
taze-write: ## Write minor version updates to package.json and install them.
	$(HYBRIDLY_EXEC) pnpx taze -w && $(MAKE) pnpm cmd="install"

.PHONY: tinker
tinker: ## Open a Laravel Tinker session.
	$(HYBRIDLY_EXEC) php artisan tinker

update-certificates: ## Generate and update SSL certificates for the project.
	@echo "$(CYAN)[INFO]: Updating SSL certificates for $(PROJECT_NAME_SLUG).test...$(RESET)"
	@mkcert \
		-cert-file ./docker/traefik/certs/$(PROJECT_NAME_SLUG).cert \
		-key-file ./docker/traefik/certs/$(PROJECT_NAME_SLUG).key \
		"*.$(PROJECT_NAME_SLUG).test" \
		"$(PROJECT_NAME_SLUG).test" \
		127.0.0.1 0.0.0.0 > /dev/null 2>&1
	@echo "$(GREEN)[SUCCESS]: SSL certificates generated.$(RESET)"
	@echo "$(CYAN)[INFO]: Copying mkcert root CA...$(RESET)"
	@cp "$$(mkcert -CAROOT)/rootCA.pem" ./docker/ssl/rootCA.pem
	@echo "$(GREEN)[SUCCESS]: mkcert root CA copied.$(RESET)"

.PHONY: vue-tsc
vue-tsc: ## Run TypeScript type checking for Vue files.
	$(HYBRIDLY_EXEC) pnpm run vue-tsc

.PHONY: volt-add
volt-add: ## Install VoltUI component using the 'make volt-add component=InputText' syntax.
	$(HYBRIDLY_EXEC) pnpx volt-vue add $(component) --outdir "./resources/modules/shared/components"
