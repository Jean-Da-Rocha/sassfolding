.PHONY: artisan
artisan: ## Run artisan commands using 'make artisan cmd=""' syntax.
	$(HYBRIDLY_EXEC) php artisan $(cmd)

.PHONY: composer
composer: ## Run composer commands using the 'make composer cmd=""' syntax.
	$(HYBRIDLY_EXEC) composer $(cmd)

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

.PHONY: phpstan
phpstan: ## Run static analysis with PHPStan.
	$(HYBRIDLY_EXEC) vendor/bin/phpstan analyze

.PHONY: pint
pint: ## Run Laravel Pint to fix coding style issues.
	$(HYBRIDLY_EXEC) vendor/bin/pint

.PHONY: tinker
tinker: ## Open a Laravel Tinker session.
	$(HYBRIDLY_EXEC) php artisan tinker
