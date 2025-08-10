# Empty by default. Set a value if you don't want to use the working directory as project name.
OVERRIDE_PROJECT_NAME ?=

# The DOCKER_DIRECTORY variable is inherited from .bashrc, .zshrc, etc.
DOCKER_DIRECTORY ?=
PROJECT_DIRECTORY := $(CURDIR)

export DOCKER_DIRECTORY
export PROJECT_DIRECTORY
export OVERRIDE_PROJECT_NAME

# Variables to override

PHP_VERSION := 8.3

include $(DOCKER_DIRECTORY)/make/main.mk
