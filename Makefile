DOCKER_COMPOSE = docker compose

# =============================================================================
# Docker Compose
# =============================================================================
.PHONY: up down restart logs db-logs redis-logs

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down -v

restart: down up

logs:
	$(DOCKER_COMPOSE) logs -f

db-logs:
	$(DOCKER_COMPOSE) logs -f postgres

redis-logs:
	$(DOCKER_COMPOSE) logs -f redis

# =============================================================================
# RoadRunner
# =============================================================================
.PHONY: rr watch

rr:
	./rr serve -c .rr.yaml

watch: rr

# =============================================================================
# Laravel commands
# =============================================================================
.PHONY: composer artisan migrate seed tinker fresh queue-work test analyse format rector lint

composer:       # composer install / update / require ...
	@composer $(filter-out $@,$(MAKECMDGOALS))

artisan:        # php artisan ...
	@php artisan $(filter-out $@,$(MAKECMDGOALS))

migrate:        # php artisan migrate --force
	php artisan migrate --force

fresh:          # migrate:fresh + seed
	php artisan migrate:fresh --force && php artisan db:seed --force

seed:           # php artisan db:seed
	php artisan db:seed --force

tinker:         # php artisan tinker
	php artisan tinker

test:           # php artisan test
	php artisan test

analyse:        # phpstan analyse
	vendor/bin/phpstan analyse

format:         # pint
	vendor/bin/pint

rector:         # rector process
	vendor/bin/rector

lint:           # pint --test + phpstan analyse
	vendor/bin/pint
	vendor/bin/phpstan analyse

# =============================================================================
# Project setup
# =============================================================================
.PHONY: install rr-get

install:
	composer install --optimize-autoloader
	vendor/bin/dload get rr
	chmod +x rr

rr-get:
	vendor/bin/dload get rr
	chmod +x rr

%::
	@:
