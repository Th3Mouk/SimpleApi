# —— Inspired by ———————————————————————————————————————————————————————————————
# http://fabien.potencier.org/symfony4-best-practices.html
# https://speakerdeck.com/mykiwi/outils-pour-ameliorer-la-vie-des-developpeurs-symfony?slide=47
# https://blog.theodo.fr/2018/05/why-you-need-a-makefile-on-your-project/
# https://www.strangebuzz.com/fr/snippets/le-makefile-parfait-pour-symfony
# Setup ————————————————————————————————————————————————————————————————————————

# Parameters
SHELL          = bash
PROJECT        = qb-api
HTTP_PORT      = 80

# Executables
EXEC_PHP       = php
COMPOSER       = composer
REDIS          = redis-cli
GIT            = git

# Alias
SYMFONY        = $(EXEC_PHP) bin/console
# if you use Docker you can replace "with: docker-composer exec my_php_container $(EXEC_PHP) bin/console"

# Executables: vendors
PEST           = ./vendor/bin/pest
PHPSTAN        = ./vendor/bin/phpstan
PSALM          = ./vendor/bin/psalm
CODESNIFFER    = ./vendor/bin/phpcs
CODESNIFFERFIX = ./vendor/bin/phpcbf
RECTOR         = ./vendor/bin/rector

# Executables: local only
SYMFONY_BIN    = symfony
BREW           = brew
DOCKER         = docker
DOCKER_COMP    = docker-compose
DOCKER_COMP_F  = -f ./docker/docker-compose.yaml -f ./docker/docker-compose.dev.yaml

# Executables: prod only
CERTBOT        = certbot

# Misc
.DEFAULT_GOAL  = help
.PHONY         =  # Not needed here, but you can put your all your targets to be sure
                  # there is no name conflict between your files and your targets.

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

warmup: ## Warmup the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
cert-install: ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: ## Serve the application with HTTPS support (add "--no-tls" to disable https)
	$(SYMFONY_BIN) serve --daemon --port=$(HTTP_PORT)

unserve: ## Stop the webserver
	$(SYMFONY_BIN) server:stop

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
windows-dev: ## Start all containers for development on windows platform
	$(DOCKER_COMP) $(DOCKER_COMP_F) -f ./docker/docker-compose.dev.windows.yaml up -d

linux-dev: ## Start all containers for development on linux platform
	$(DOCKER_COMP) $(DOCKER_COMP_F) -f ./docker/docker-compose.dev.unix.yaml up -d

macos-dev: ## Start all containers for development on macOS platform
	$(DOCKER_COMP) $(DOCKER_COMP_F) -f ./docker/docker-compose.dev.unix.yaml up -d

down: ## Stop all the containers
	$(DOCKER_COMP) $(DOCKER_COMP_F) down --remove-orphans

docker-rebuild: ## Builds the PHP image
	$(DOCKER_COMP) $(DOCKER_COMP_F) build

check: ## Docker check
	@$(DOCKER) info > /dev/null 2>&1                                                        # Docker is up
	@test '"healthy"' = `$(DOCKER) inspect --format "{{json .State.Health.Status }}" sb-db` # Db container is up and healthy

bash: ## Connect to the application container
	$(DOCKER) container exec -it php bash

## —— Project 🐝 ———————————————————————————————————————————————————————————————
commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:drop --force
	$(SYMFONY) doctrine:schema:create
	$(SYMFONY) doctrine:schema:validate
	$(SYMFONY) hautelook:fixtures:load --no-interaction

init-snippet: ## Initialize a new snippet
	$(SYMFONY) $(PROJECT):init-snippet

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: ## Run main functional and unit tests
	$(eval testsuite ?= 'smoke,unit,integration,functional')
	$(eval filter ?= 'tests')
	$(PEST) --testsuite=$(testsuite) --filter=$(filter) --stop-on-failure

test-inte:
	$(eval filter ?= 'tests/Integration')
	$(PEST) --testsuite=integration --filter=$(filter)

test-func:
	$(eval filter ?= 'tests/Functional')
	$(PEST) --testsuite=functional --filter=$(filter)

test-smoke:
	$(eval filter ?= 'tests/Smoke')
	$(PEST) --testsuite=smoke --filter=$(filter)

test-unit:
	$(eval filter ?= 'tests/Unit')
	$(PEST) --testsuite=unit --filter=$(filter)

test-all: ## Run all tests
	$(PEST) --stop-on-failure

## —— Continuous Integration ✨ ————————————————————————————————————————————————
static-analysis: psalm stan ## Run the static analysis (Psalm & PHPStan)

psalm: ## Run Psalm
	$(PSALM)

stan: ## Run PHPStan
	$(PHPSTAN) analyse -c phpstan.neon --memory-limit 1G

cs: ## Lint files with CodeSniffer
	$(CODESNIFFER)

fix-cs: ## Fix files with CodeSniffer
	$(CODESNIFFERFIX)

rector-dr: ## Refactoring in dry run mode
	$(RECTOR) --dry-run

rector: ## Refactoring
	$(RECTOR)

ci: rector-dr cs static-analysis test

fix-all: rector fix-cs

## —— Documentations 📚 ————————————————————————————————————————————————————————

docs: ## Generate API documentations at OpenAPI format
	$(SYMFONY) nelmio:apidoc:dump > doc/default.json

## —— Deploy & Prod 🚀 —————————————————————————————————————————————————————————
