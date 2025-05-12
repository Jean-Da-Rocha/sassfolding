ifeq ($(UNIX_SHELL_NAME),Darwin)
	SED_IN_PLACE = sed -i ''
else
	SED_IN_PLACE = sed -i
endif

.PHONY: configure-husky-hooks
configure-husky-hooks: ## Dynamically bind Husky hooks to the right Docker container.
	@echo "$(CYAN)[INFO]: Configuring Husky hooks...$(RESET)"
	@$(SED_IN_PLACE) -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/commit-msg
	@$(SED_IN_PLACE) -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/pre-commit
	@$(SED_IN_PLACE) -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/pre-push
	@echo "$(GREEN)[SUCCESS]: Husky hooks ready.$(RESET)"

.PHONY: setup-local-environment
setup-local-environment: ## Set up the local environment from the .env.example file.
	@echo "$(CYAN)[INFO]: Setting up the local environment...$(RESET)"
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.example > .env
	@$(SED_IN_PLACE) "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env
	@$(SED_IN_PLACE) "s|^DNSMASQ_FORWARD_PORT=.*|DNSMASQ_FORWARD_PORT=$(DNSMASQ_FORWARD_PORT)|" .env
	@echo "$(GREEN)[SUCCESS]: local environment ready.$(RESET)"

.PHONY: setup-testing-environment
setup-testing-environment: ## Set up the testing environment from the .env.testing.example file.
	@echo "$(CYAN)[INFO]: Setting up the testing environment...$(RESET)"
	@if [ ! -f .env.testing ]; then cp .env.testing.example .env.testing; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.testing.example > .env.testing
	@echo "$(GREEN)[SUCCESS]: testing environment ready.$(RESET)"
