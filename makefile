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
	docker exec -i $(CONTAINER) vendor/bin/phpunit

# QA tools
phpstan:
	docker exec -i $(CONTAINER) php -d memory_limit=1G vendor/bin/phpstan analyse

phpcs:
	docker exec -i $(CONTAINER) vendor/bin/phpcs

phpcbf:
	docker exec -i $(CONTAINER) vendor/bin/phpcbf

audit:
	docker exec -i $(CONTAINER) composer audit --abandoned=report

# PHPQA (qa-runner is a private repo mounted to /qa-runner via docker-compose.override.yml)
phpqa:
	docker exec -i $(CONTAINER) php /qa-runner/bin/qa-runner run

phpqa-full:
	docker exec -i $(CONTAINER) php /qa-runner/bin/qa-runner run; true
	@( command -v wslview > /dev/null 2>&1 && wslview storage/qa/build/index.html \
	|| command -v xdg-open > /dev/null 2>&1 && xdg-open storage/qa/build/index.html \
	|| command -v open > /dev/null 2>&1 && open storage/qa/build/index.html \
	|| echo "Open storage/qa/build/index.html in your browser" ) 2>/dev/null

shell:
	docker exec -it $(CONTAINER) bash
