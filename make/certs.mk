update-certificates: ## Generate and update SSL certificates for the project.
	@echo "$(CYAN)[INFO]: Adding Certificate Authority to trust stores...$(RESET)"
	@if [ -f "$$(mkcert -CAROOT)/rootCA.pem" ]; then \
		echo "$(YELLOW)[WARNING]: Certificate Authority already added to trust stores.$(RESET)"; \
	else \
		mkcert -install; \
		echo "$(GREEN)[SUCCESS]: Certificate Authority added to trust stores.$(RESET)"; \
	fi
	@echo "$(CYAN)[INFO]: Updating SSL certificates for $(PROJECT_NAME_SLUG).test...$(RESET)"
	@mkcert \
		-cert-file ./docker/traefik/certs/$(PROJECT_NAME_SLUG).cert \
		-key-file ./docker/traefik/certs/$(PROJECT_NAME_SLUG).key \
		"*.$(PROJECT_NAME_SLUG).test" \
		"$(PROJECT_NAME_SLUG).test" \
		127.0.0.1 0.0.0.0 > /dev/null 2>&1
	@echo "$(GREEN)[SUCCESS]: SSL certificates generated.$(RESET)"
	@echo "$(CYAN)[INFO]: Copying mkcert root CA...$(RESET)"
	@cp "$$(mkcert -CAROOT)/rootCA.pem" ./docker/hybridly/ssl/rootCA.pem
	@echo "$(GREEN)[SUCCESS]: mkcert root CA copied.$(RESET)"
