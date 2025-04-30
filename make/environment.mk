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
