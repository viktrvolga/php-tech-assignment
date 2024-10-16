help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Run: make <target> where <target> is one of the following'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

IMAGE_NAME = cliq-digital
CONTAINER = docker run --rm -it -v "$(PWD)":/app -w /app

build:
	@docker build -t ${IMAGE_NAME} .

dependencies:
	$(CONTAINER) $(IMAGE_NAME) composer install -ov

test:
	$(CONTAINER) $(IMAGE_NAME) composer test

verify:
	$(CONTAINER) $(IMAGE_NAME) php public/index.php

coverage:
	$(CONTAINER) $(IMAGE_NAME) composer coverage

linter:
	$(CONTAINER) $(IMAGE_NAME) composer phpstan

.DEFAULT_GOAL := help
