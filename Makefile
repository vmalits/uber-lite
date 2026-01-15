DOCKER_COMPOSE = docker compose

# =============================================================================
# Docker Compose
# =============================================================================
.PHONY: up down restart logs db-logs redis-logs bash build build-no-cache

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down -v

restart: stop up

stop:
	$(DOCKER_COMPOSE) stop

logs:
	$(DOCKER_COMPOSE) logs -f

db-logs:
	$(DOCKER_COMPOSE) logs -f postgres

redis-logs:
	$(DOCKER_COMPOSE) logs -f redis

bash:           # Open shell in app container
	$(DOCKER_COMPOSE) exec app sh

build:          # Build Docker images
	$(DOCKER_COMPOSE) build

build-no-cache: # Build Docker images without cache
	$(DOCKER_COMPOSE) build --no-cache

# =============================================================================
# RoadRunner (in Docker container)
# =============================================================================
.PHONY: rr watch reset

rr:             # Start RoadRunner in container
	$(DOCKER_COMPOSE) exec app rr serve -c .rr.yaml

reset:          # Reset RoadRunner in container
	$(DOCKER_COMPOSE) exec app rr reset -c .rr.yaml

watch: rr

# =============================================================================
# Laravel commands (in Docker container)
# =============================================================================
.PHONY: composer artisan migrate seed tinker fresh queue-work test analyse format rector lint docs coverage

composer:       # composer install / update / require ... (in container)
	$(DOCKER_COMPOSE) exec app composer $(filter-out $@,$(MAKECMDGOALS))

artisan:        # php artisan ... (in container)
	$(DOCKER_COMPOSE) exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate:        # php artisan migrate --force (in container)
	$(DOCKER_COMPOSE) exec app php artisan migrate --force

fresh:          # migrate:fresh + seed (in container)
	$(DOCKER_COMPOSE) exec app php artisan migrate:fresh --force && $(DOCKER_COMPOSE) exec app php artisan db:seed --force

seed:           # php artisan db:seed (in container)
	$(DOCKER_COMPOSE) exec app php artisan db:seed --force

tinker:         # php artisan tinker (in container)
	$(DOCKER_COMPOSE) exec app php artisan tinker

test:           # php artisan test (in container)
	$(DOCKER_COMPOSE) exec -e APP_ENV=testing app php artisan test

coverage:       # pest coverage (HTML + text) (in container)
	$(DOCKER_COMPOSE) exec app vendor/bin/pest --type-coverage

analyse:        # phpstan analyse (in container)
	$(DOCKER_COMPOSE) exec app vendor/bin/phpstan analyse --memory-limit=4G

format:         # pint (in container)
	$(DOCKER_COMPOSE) exec app vendor/bin/pint

rector:         # rector process (in container)
	$(DOCKER_COMPOSE) exec app vendor/bin/rector

lint:           # pint --test + phpstan analyse (in container)
	$(DOCKER_COMPOSE) exec app vendor/bin/pint
	$(DOCKER_COMPOSE) exec app vendor/bin/phpstan analyse

docs:           # scribe:generate (in container)
	$(DOCKER_COMPOSE) exec app php artisan scribe:generate

# =============================================================================
# Project setup (in Docker container)
# =============================================================================
.PHONY: install rr-get

install:        # Install dependencies and RoadRunner in container
	$(DOCKER_COMPOSE) exec app composer install --optimize-autoloader
	$(DOCKER_COMPOSE) exec app vendor/bin/dload get rr
	$(DOCKER_COMPOSE) exec app chmod +x rr

rr-get:         # Download RoadRunner binary in container
	$(DOCKER_COMPOSE) exec app vendor/bin/dload get rr
	$(DOCKER_COMPOSE) exec app chmod +x rr

%::
	@:
