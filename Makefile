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
  PROJECT_NAME := $(shell read -p "Enter the project name: " PROJECT_NAME && echo $$PROJECT_NAME)
endif

PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

DOCKER ?= @docker
DOCKER_COMPOSE ?= $(DOCKER) compose
HORIZON_EXEC ?= $(DOCKER_COMPOSE) exec -it horizon
HYBRIDLY_EXEC ?= $(DOCKER_COMPOSE) exec -it hybridly
HYBRIDLY_RUNNER ?= $(DOCKER_COMPOSE) run --rm --no-deps hybridly

.PHONY: artisan
artisan:
	$(HYBRIDLY_EXEC) php artisan $(cmd)

.PHONY: build
build: restore-dns
	$(DOCKER) compose build

.PHONY: composer
composer:
	$(HYBRIDLY_EXEC) composer $(cmd)

.PHONY: destroy
destroy:
	$(DOCKER_COMPOSE) down --remove-orphans --volumes
	$(DOCKER) system prune --all --force --volumes

.PHONY: eslint
eslint:
	$(HYBRIDLY_EXEC) pnpm run lint:fix

.PHONY: horizon-continue
horizon-continue:
	$(HORIZON_EXEC) php artisan horizon:continue

.PHONY: horizon-pause
horizon-pause:
	$(HORIZON_EXEC) php artisan horizon:pause

.PHONY: horizon-start
horizon-start:
	$(HORIZON_EXEC) php artisan horizon

.PHONY: horizon-terminate
horizon-terminate:
	$(HORIZON_EXEC) php artisan horizon:terminate

.PHONY: install
install: install-all-deps setup-local-environment setup-testing-environment update-certificates

.PHONY: install-all-deps
install-all-deps: install-composer-deps install-pnpm-deps

.PHONY: install-composer-deps
install-composer-deps:
	$(HYBRIDLY_RUNNER) composer install --prefer-dist --no-interaction --no-progress

.PHONY: install-pnpm-deps
install-pnpm-deps:
	$(HYBRIDLY_RUNNER) pnpm install --frozen-lockfile --force

.PHONY: phpstan
phpstan:
	$(HYBRIDLY_EXEC) vendor/bin/phpstan analyze

.PHONY: pint
pint:
	$(HYBRIDLY_EXEC) vendor/bin/pint

.PHONY: pnpm
pnpm:
	$(HYBRIDLY_EXEC) pnpm $(or $(cmd), --version)

.PHONY: purge
purge:
	$(DOCKER) compose down --remove-orphans --volumes
	$(DOCKER) network prune --force
	$(DOCKER) volume prune --force
	$(DOCKER) image prune --force

.PHONY: rebuild
rebuild:
	$(DOCKER) compose down --remove-orphans --volumes
	@$(MAKE) restore-dns
	$(DOCKER) compose build
	@$(MAKE) setup-dns
	$(DOCKER) compose up --detach

.PHONY: restart
restart: stop start

.PHONY: restore-dns
restore-dns:
	@echo "$(CYAN)[INFO]: Restoring default DNS...$(RESET)"
ifeq ($(shell uname),Darwin)
	@echo "$(CYAN)[INFO]: macOS: Restoring default DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@sudo rm -f /etc/resolver/$(DNS_DOMAIN)
	@echo "$(GREEN)[SUCCESS]: Removed macOS DNS resolver for *.$(DNS_DOMAIN).$(RESET)"
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
setup-dns:
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
	@echo "$(CYAN)[INFO]: Generating APP_KEY for $(1) environments...$(RESET)"
	$(HYBRIDLY_RUNNER) php artisan key:generate $(3)
	@echo "$(GREEN)[SUCCESS]: $(1) environment ready.$(RESET)"
endef

.PHONY: setup-local-environment
setup-local-environment:
	$(call setup_environment,local,.env,)

.PHONY: setup-testing-environment
setup-testing-environment:
	$(call setup_environment,testing,.env.testing,--env=testing)

.PHONY: start
start:
	$(DOCKER_COMPOSE) up --detach --remove-orphans

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) down --remove-orphans

.PHONY: taze
taze:
	$(HYBRIDLY_EXEC) pnpx taze

.PHONY: taze-major
taze-major:
	$(HYBRIDLY_EXEC) pnpx taze major

.PHONY: tinker
tinker:
	$(HYBRIDLY_EXEC) php artisan tinker

.PHONY: update-certificates
update-certificates:
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
vue-tsc:
	$(HYBRIDLY_EXEC) pnpm run vue-tsc
