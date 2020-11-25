.ONESHELL:
SHELL := /bin/bash
docker_compose := docker-compose -f docker/docker-compose.yml

include docker/docker.env
export $(shell sed 's/=.*//' docker/docker.env)

#
# Developer and development targets
#
help:
	@echo 'Usage: make [target] ...'
	@echo
	@echo 'targets:'
	@echo -e "$$(grep -hE '^\S+:.*##' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' -e 's/^\(.\+\):\(.*\)/\\x1b[36m\1\\x1b[m:\2/' | column -c2 -t -s :)"

initial-setup: composer-install cache-warmup npm-install ## Perform basic commands to prepare an initial set-up

run: ## Starts the application containers
	@$(docker_compose) up

destroy: ## Destroys the application containers
	@$(docker_compose) down

restart: destroy run ## Destroys and re-runs the the application containers

bash-php: ## Open an interactive Bash shell into the PHP container
	@$(docker_compose) exec php sh -c "bash"

bash-node: ## Open an interactive Bash shell into the Node container
	@$(docker_compose) exec node sh -c "bash"

flush-database: ## Flush the database content
	@$(docker_compose) exec database sh -c "redis-cli -c FLUSHALL"

all-checks: static-analysis coding-standards security-check test ## Run all the checks

test: unit-test functional-test ## Run all tests

unit-test: ## Run unit tests
	@$(docker_compose) run -T --rm php sh -c "vendor/bin/phpunit tests/Unit"

functional-test: ## Run functional tests
	@export COMPOSE_PROJECT_NAME=functional-test WEBSERVER_EXPOSED_PORT=8080 DATABASE_EXPOSED_PORT=6380 NODE_EXPOSED_PORT=3030
	@$(docker_compose) up --detach webserver database
	@$(docker_compose) run -T --rm php sh -c "APP_ENV=test vendor/bin/phpunit tests/Functional"
	@$(docker_compose) rm --stop --force -v webserver database

coding-standards: ## Check coding standards rules
	@$(docker_compose) run -T --rm php sh -c "vendor/bin/phpcs --standard=PSR2 src/"

static-analysis: ## Run static analysis
	@$(docker_compose) run -T --rm php sh -c "vendor/bin/phpstan analyse -l 1 src"

security-check:
	@$(docker_compose) run -T --rm php sh -c "symfony check:security"

cache-warmup: ## Warm up the Symfony cache
	@$(docker_compose) run --rm php sh -c "bin/console cache:warmup"

composer-install: ## Run composer install
	@$(docker_compose) run --rm php sh -c "composer install"

npm-install: ## Run npm install
	@$(docker_compose) run --rm node sh -c "npm install"

unit-test-node:
	@$(docker_run) run --rm node sh -c "npm run test:unit"

#
# DevOps targets
#
build-images:
	docker build --target dev -f docker/php/Dockerfile -t jesugmz/fullstackdemo-php .
	docker build --target dev -f docker/node/Dockerfile -t jesugmz/fullstackdemo-node .

push-dev-images:
	docker push jesugmz/fullstackdemo-php
	docker push jesugmz/fullstackdemo-node

.DEFAULT_GOAL := help
