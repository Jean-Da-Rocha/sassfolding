# Empty by default. Set a value if you don't want to use the working directory as project name.
OVERRIDE_PROJECT_NAME ?=

DOCKER_DIRECTORY ?=
PROJECT_DIRECTORY := $(CURDIR)

export DOCKER_DIRECTORY
export PROJECT_DIRECTORY
export OVERRIDE_PROJECT_NAME

include $(DOCKER_DIRECTORY)/make/main.mk
