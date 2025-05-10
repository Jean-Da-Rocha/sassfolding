.PHONY: configure-husky-hooks
configure-husky-hooks: ## Dynamically bind Husky hooks to the right Docker container.
	@echo "$(CYAN)[INFO]: Configuring Husky hooks...$(RESET)"
	@sed -i'' -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/commit-msg
	@sed -i'' -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/pre-commit
	@sed -i'' -E "s|[a-zA-Z0-9_-]+-hybridly|$(PROJECT_NAME_SLUG)-hybridly|g" ./.husky/pre-push
	@echo "$(GREEN)[SUCCESS]: Husky hooks ready.$(RESET)"

.PHONY: setup-local-environment
setup-local-environment: ## Set up the local environment from the .env.example file.
	@echo "$(CYAN)[INFO]: Setting up the local environment...$(RESET)"
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.example > .env
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env
	@sed -i "s|^DNSMASQ_FORWARD_PORT=.*|DNSMASQ_FORWARD_PORT=$(DNSMASQ_FORWARD_PORT)|" .env
	@echo "$(GREEN)[SUCCESS]: local environment ready.$(RESET)"

.PHONY: setup-testing-environment
setup-testing-environment: ## Set up the testing environment from the .env.testing.example file.
	@echo "$(CYAN)[INFO]: Setting up the testing environment...$(RESET)"
	@if [ ! -f .env.testing ]; then cp .env.testing.example .env.testing; fi
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.testing.example > .env.testing
	@echo "$(GREEN)[SUCCESS]: testing environment ready.$(RESET)"
