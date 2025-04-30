.PHONY: build
build: ## Build the docker images for the project, optionally without cache using 'make build keep-cache=0' syntax.
	$(DOCKER_COMPOSE) build $(if $(filter 0, $(keep-cache)), --no-cache)

.PHONY: destroy
destroy: ## Tear down the project, removing volumes and pruning Docker system.
	$(DOCKER) system prune --all --force --volumes

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

.PHONY: start
start: ## Start the Docker containers for the project.
	$(DOCKER_COMPOSE) up --detach --remove-orphans

.PHONY: stop
stop: ## Stop the Docker containers for the project, optionally removing volumes using 'make stop keep-volumes=0' syntax.
	$(DOCKER_COMPOSE) down --remove-orphans $(if $(filter 0, $(keep-volumes)), --volumes)
