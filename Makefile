PROJECT_NAME := $(shell grep '^COMPOSE_PROJECT_NAME=' .env | cut -d'=' -f2)

ifeq ($(PROJECT_NAME),)
  PROJECT_NAME := $(shell read -p "Enter the project name: " PROJECT_NAME && echo $$PROJECT_NAME)
endif

PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

install: setup_local_environment setup_testing_environment update-certificates update-hosts

setup_local_environment:
	@echo "Setting up the local environment..."

	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo ".env file created"; \
	fi

	@echo "Substituting environment variables in .env file..."
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.example > .env

	@echo "Replacing COMPOSE_PROJECT_NAME in .env file..."
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env
	@echo ".env file updated with project name and URL"

setup_testing_environment:
	@echo "Setting up the testing environment..."

	@if [ ! -f .env.testing ]; then \
		cp .env.testing.example .env.testing; \
		echo ".env.testing file created"; \
	fi

	@echo "Substituting environment variables in .env.testing file..."
	@COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG) envsubst < .env.testing.example > .env.testing

	@echo "Replacing COMPOSE_PROJECT_NAME in .env.testing file..."
	@sed -i "s|^COMPOSE_PROJECT_NAME=.*|COMPOSE_PROJECT_NAME=$(PROJECT_NAME_SLUG)|" .env.testing
	@echo ".env.testing file updated with project name and URL"

update-certificates:
	@echo "Updating SSL certificates for ${PROJECT_NAME_SLUG}..."
	@if [ ! -d "./certs" ]; then \
		mkdir ./certs; \
		echo "Created certs directory"; \
	fi
	@rm -rf ./certs/*
	@mkcert -key-file ./certs/${PROJECT_NAME_SLUG}.test.key -cert-file ./certs/${PROJECT_NAME_SLUG}.test.crt "*.${PROJECT_NAME_SLUG}.test" ${PROJECT_NAME_SLUG}.test 0.0.0.0 127.0.0.1 > /dev/null 2>&1
	@echo "\n✅ SSL certificates for ${PROJECT_NAME_SLUG}.test updated!"

update-hosts:
	@echo "Adding ${PROJECT_NAME_SLUG}.test to the hosts file..."
	@if ! grep -q "${PROJECT_NAME_SLUG}.test" /etc/hosts; then \
		echo "127.0.0.1 ${PROJECT_NAME_SLUG}.test" | sudo tee -a /etc/hosts > /dev/null; \
		echo "Hosts file updated with ${PROJECT_NAME_SLUG}.test"; \
	else \
		echo "Entry for ${PROJECT_NAME_SLUG}.test already exists in /etc/hosts"; \
	fi
