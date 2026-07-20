CONTAINER = aviso_dockerized_php84_1
COMPOSE = docker-compose --env-file .docker/.env

@all:
	cat makefile

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

composer-install:
	docker exec -i $(CONTAINER) composer install

composer-update:
	docker exec -i $(CONTAINER) composer update

phpunit:
	docker exec -i $(CONTAINER) vendor/bin/phpunit tests

shell:
	docker exec -it $(CONTAINER) bash
