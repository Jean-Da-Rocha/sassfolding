.PHONY: install
install: ## Install dependencies and set up the local and testing environments.
	@$(MAKE) restore-dns
	@$(MAKE) setup-local-environment
	@$(MAKE) setup-testing-environment
	@$(MAKE) configure-husky-hooks
	@$(MAKE) update-certificates
	@$(MAKE) build keep-cache=0
	@$(HYBRIDLY_RUNNER) composer install --prefer-dist --no-interaction --no-progress
	@$(HYBRIDLY_RUNNER) pnpm install --frozen-lockfile --force
	@echo "$(CYAN)[INFO]: Generating APP_KEY for local and testing environments...$(RESET)"
	@$(HYBRIDLY_RUNNER) php artisan key:generate
	@$(HYBRIDLY_RUNNER) php artisan key:generate --env=testing
	@$(MAKE) setup-dns
	@$(MAKE) restart

.PHONY: restore-dns
restore-dns: ## Restore the default DNS settings.
	@echo "$(CYAN)[INFO]: Restoring default DNS...$(RESET)"
ifeq ($(UNIX_SHELL_NAME),Darwin)
	@echo "$(CYAN)[INFO]: macOS: Restoring default DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ -f /etc/resolver/$(DNS_DOMAIN) ]; then \
		sudo rm -f /etc/resolver/$(DNS_DOMAIN); \
		echo "$(GREEN)[SUCCESS]: Removed macOS DNS resolver for *.$(DNS_DOMAIN).$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: No macOS DNS resolver for *.$(DNS_DOMAIN) to remove.$(RESET)"; \
	fi
else ifeq ($(UNIX_SHELL_NAME),Linux)
	@echo "$(CYAN)[INFO]: Linux: Restoring default DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ -f /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf ]; then \
		sudo rm /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf; \
		sudo systemctl restart systemd-resolved; \
		echo "$(GREEN)[SUCCESS]: Removed Linux systemd-resolved config for *.$(DNS_DOMAIN).$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: No systemd-resolved DNS override to remove.$(RESET)"; \
	fi
else
	@echo "$(RED)[ERROR]: Unsupported OS '$(UNIX_SHELL_NAME)'. DNS setup aborted.$(RESET)"
	@exit 1
endif

.PHONY: setup-dns
setup-dns: ## Set up DNS resolver for the provided top level domain (TLD).
	@echo "$(CYAN)[INFO]: Setting up DNS for *.$(DNS_DOMAIN)...$(RESET)"
ifeq ($(UNIX_SHELL_NAME),Darwin)
	@echo "$(CYAN)[INFO]: macOS: Configuring DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ ! -f /etc/resolver/$(DNS_DOMAIN) ]; then \
		echo "$(CYAN)[INFO]: Adding DNS resolver configuration...$(RESET)"; \
		sudo mkdir -p /etc/resolver; \
		echo "nameserver $(DNSMASQ_IP_ADDRESS)" | sudo tee /etc/resolver/$(DNS_DOMAIN) > /dev/null; \
		echo "$(GREEN)[SUCCESS]: macOS DNS resolver added for *.$(DNS_DOMAIN)$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: DNS resolver for $(DNS_DOMAIN) already exists. Skipping...$(RESET)"; \
	fi
else ifeq ($(UNIX_SHELL_NAME),Linux)
	@echo "$(CYAN)[INFO]: Linux: Configuring DNS resolver for *.$(DNS_DOMAIN)...$(RESET)"
	@if [ ! -d /etc/systemd/resolved.conf.d ]; then \
		sudo mkdir -p /etc/systemd/resolved.conf.d; \
		echo "$(CYAN)[INFO]: Created /etc/systemd/resolved.conf.d directory.$(RESET)"; \
	fi
	@echo "[Resolve]" | sudo tee /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "DNS=$(DNSMASQ_IP_ADDRESS):$(DNSMASQ_FORWARD_PORT)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@echo "Domains=$(DNS_DOMAIN)" | sudo tee -a /etc/systemd/resolved.conf.d/$(DNS_DOMAIN).conf > /dev/null
	@if systemctl is-active --quiet systemd-resolved; then \
		sudo systemctl restart systemd-resolved; \
		echo "$(GREEN)[SUCCESS]: Linux systemd-resolved DNS config added for *.$(DNS_DOMAIN)$(RESET)"; \
	else \
		echo "$(YELLOW)[WARNING]: systemd-resolved is not running. Please start it manually.$(RESET)"; \
	fi
else
	@echo "$(RED)[ERROR]: Unsupported OS '$(UNIX_SHELL_NAME)'. DNS setup aborted.$(RESET)"
	@exit 1
endif
