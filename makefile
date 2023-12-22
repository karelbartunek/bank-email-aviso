UID = $$(id -u)
PWD = $$(pwd)

@all:
	cat makefile

phpunit:
	docker exec -i package_php74_1 vendor/bin/phpunit tests