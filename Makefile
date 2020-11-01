DOCKER_EXEC = docker-compose exec fpm
DOCKER_RUN = docker-compose run --rm fpm

.PHONY: up
up:
	docker-compose up -d

.PHONY: xdebug-on
xdebug-on:
	docker-compose stop fpm
	XDEBUG_ENABLED=1 docker-compose up -d fpm

.PHONY: xdebug-off
xdebug-off:
	docker-compose stop fpm
	XDEBUG_ENABLED=0 docker-compose up -d fpm

.PHONY: down
down:
	docker-compose down --remove-orphans

vendor:
	$(DOCKER_RUN) composer install

.PHONY: setup
setup:
	make down
	rm -rf vendor
	make vendor
	make up

.PHONY: tests
tests:
	$(DOCKER_RUN) ./vendor/bin/simple-phpunit ${path}

.PHONY: coverage
coverage:
	XDEBUG_ENABLED=1 $(DOCKER_RUN) vendor/bin/simple-phpunit --coverage-html var/coverage
