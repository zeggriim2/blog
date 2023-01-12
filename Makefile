#---VARIABLES---------------------------------#

#---ENVIRONNEMENT---#
ENV?=dev
#------------#

#---DOCKER---#
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_UP_DEV = $(DOCKER_COMPOSE) --env-file .env.dev.local up -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) stop
DOCKER_COMPOSE_DOWN = $(DOCKER_COMPOSE) down
#------------#

#---SYMFONY---#
SYMFONY = symfony
SYMFONY_SERVER_START = $(SYMFONY) serve -d
SYMFONY_SERVER_STOP = $(SYMFONY) server:stop
SYMFONY_CHECK = $(SYMFONY) security:check
SYMFONY_CONSOLE = $(SYMFONY) console
SYMFONY_LINT = $(SYMFONY_CONSOLE) lint:
#------------#

#---COMPOSER---#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update
#------------#

#---YARN-----#
YARN = yarn
YARN_INSTALL = $(YARN) add --force
YARN_UPDATE = $(YARN) upgrade
YARN_BUILD = $(YARN) run build
YARN_DEV = $(YARN) run dev
YARN_WATCH = $(YARN) run watch
#------------#



## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Symfon-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#

## === 🆘  INITIALISATION ==================================================
init-install: ## Initialisation de l'application
	@make composer-install
	@make init-prepare
.PHONY: init-install

init-prepare: ## Initialisation delete BDD, Create BDD, migration, Load Fixtures
	@make sf-dd
	@make sf-dc
	@make sf-dmm
	@make sf-fixtures
.PHONY: init-prepare

## === 🐋  DOCKER ================================================
docker-up: ## Start docker containers.
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-up

docker-up-dev: ## Start docker containers DEV.
	$(DOCKER_COMPOSE) --env-file ./.env.$(ENV).local up -d
.PHONY: docker-docker-up-dev

docker-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop

docker-down: ## Down docker containers.
	$(DOCKER_COMPOSE_DOWN)
.PHONY: docker-down
#---------------------------------------------#

## === 🎛️  SYMFONY ===============================================
sf-start: ## Start symfony server.
	$(SYMFONY_SERVER_START)
.PHONY: sf-start

sf-stop: ## Stop symfony server.
	$(SYMFONY_SERVER_STOP)
.PHONY: sf-stop

sf-cc: ## Clear symfony cache.
	$(SYMFONY_CONSOLE) cache:clear
.PHONY: sf-cc

sf-dc: ## Create symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists
.PHONY: sf-dc

sf-dd: ## Drop symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force
.PHONY: sf-dd

sf-mm: ## Make migrations.
	$(SYMFONY_CONSOLE) make:migration
.PHONY: sf-mm

sf-dmm: ## Migrate.
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction
.PHONY: sf-dmm

sf-fixtures: ## Load fixtures.
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction
.PHONY: sf-fixtures

sf-dump-env: ## Dump env.
	$(SYMFONY_CONSOLE) debug:dotenv
.PHONY: sf-dump-env

sf-dump-routes: ## Dump routes.
	$(SYMFONY_CONSOLE) debug:router
.PHONY: sf-dump-routes

sf-open: ## Open symfony server.
	$(SYMFONY) open:local
.PHONY: sf-open

sf-check-requirements: ## Check requirements.
	$(SYMFONY) check:requirements
.PHONY: sf-check-requirements

## === 📦  COMPOSER ==============================================
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)
.PHONY: composer-update
#---------------------------------------------#

## === 📦  YARN ===================================================
yarn-install: ## Install npm dependencies.
	$(YARN_INSTALL)
.PHONY: yarn-install

yarn-update: ## Update npm dependencies.
	$(YARN_UPDATE)
.PHONY: yarn-update

yarn-build: ## Build assets.
	$(YARN_BUILD)
.PHONY: yarn-build

yarn-dev: ## Build assets in dev mode.
	$(YARN_DEV)
.PHONY: yarn-dev

yarn-watch: ## Watch assets.
	$(YARN_WATCH)
.PHONY: yarn-watch
#---------------------------------------------#

## === 📦  FIX ==============================================
cs-fixer: ## PHP CS FIXER
	php vendor/bin/php-cs-fixer -vvv
.PHONY: cs-fixer
#---------------------------------------------#

## === 📦  TEST ==============================================
test-valide: ## Check la validité du schéma de la BDD
	php bin/console doctrine:schema:validate
.PHONY: test-valide
#---------------------------------------------#