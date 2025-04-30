.PHONY: eslint
eslint: ## Run ESLint with automatic fixing.
	$(HYBRIDLY_EXEC) pnpm run lint:fix

.PHONY: pnpm
pnpm: ## Run pnpm commands using the 'make pnpm cmd=""' syntax.
	$(HYBRIDLY_EXEC) pnpm $(or $(cmd), --version)

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

.PHONY: volt-add
volt-add: ## Install VoltUI component using the 'make volt-add component=InputText' syntax.
	$(HYBRIDLY_EXEC) pnpx volt-vue add $(component) --outdir "./resources/modules/shared/components" --no-deps

.PHONY: vue-tsc
vue-tsc: ## Rune TypeScript type checking for {.ts,.vue} files
	$(HYBRIDLY_EXEC) pnpm run vue-tsc

