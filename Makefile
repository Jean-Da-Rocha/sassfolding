# Empty by default. Set a value if you don't want to use the working directory as project name.
OVERRIDE_PROJECT_NAME ?=

PROJECT_DIRECTORY := $(CURDIR)
export PROJECT_DIRECTORY

DOCKER_DIRECTORY := /home/juanito/sassfolding-docker-local
export DOCKER_DIRECTORY

UNIX_SHELL_NAME := $(shell uname -s)

CYAN   := \033[0;36m
GREEN  := \033[0;32m
RED    := \033[0;31m
RESET  := \033[0m
YELLOW := \033[0;33m

ifndef VERBOSE
	MAKEFLAGS += --no-print-directory
endif

DNS_DOMAIN := test
DNSMASQ_FORWARD_PORT := $(if $(findstring Darwin,$(UNIX_SHELL_NAME)),53,5353)
DNSMASQ_IP_ADDRESS := 127.0.0.1

ifneq ("$(wildcard .env)","")
  PROJECT_NAME := $(shell grep '^COMPOSE_PROJECT_NAME=' .env | cut -d'=' -f2)
endif

ifeq ($(PROJECT_NAME),)
  PROJECT_NAME := $(shell basename $(PWD))
endif

PROJECT_NAME := $(if $(OVERRIDE_PROJECT_NAME), $(OVERRIDE_PROJECT_NAME), $(PROJECT_NAME))
PROJECT_NAME_SLUG := $(shell echo $(PROJECT_NAME) | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d -c 'a-z0-9-')

DOCKER ?= @docker
DOCKER_COMPOSE ?= $(DOCKER) compose
HORIZON_EXEC ?= $(DOCKER_COMPOSE) exec -it horizon
HYBRIDLY_EXEC ?= $(DOCKER_COMPOSE) exec -it hybridly
HYBRIDLY_RUNNER ?= $(DOCKER_COMPOSE) run --rm --no-deps hybridly

-include $(DOCKER_DIRECTORY)/make/backend.mk
-include $(DOCKER_DIRECTORY)/make/certs.mk
-include $(DOCKER_DIRECTORY)/make/docker.mk
-include $(DOCKER_DIRECTORY)/make/environment.mk
-include $(DOCKER_DIRECTORY)/make/frontend.mk
-include $(DOCKER_DIRECTORY)/make/install.mk

.PHONY: help
help:
	@echo 'Available make commands:'
	@grep -Eh '^[a-zA-Z_0-9%-]+:.*?## .*$$' Makefile make/*.mk | awk 'BEGIN {FS = ":.*?## "}; {printf "    - ${CYAN}%-25s${RESET}: %s\n", $$1, $$2}'
	@echo
