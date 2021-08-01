init: docker-down-clear \
	api-clear \
	docker-pull docker-build docker-up \
	api-init
up: docker-up
down: docker-down
restart: down up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

api-clear:
	docker run --rm -v ${PWD}:/app -w /app alpine sh -c 'rm -rf runtime/cache/* runtime/logs/*'

api-init: api-permissions api-composer-install api-wait-db api-migrations

api-permissions:
	docker run --rm -v ${PWD}:/app -w /app alpine chmod -R 777 runtime

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-composer-update:
	docker-compose run --rm api-php-cli composer update

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-maria:3306 -t 30

api-migrations:
	docker-compose run --rm api-php-cli composer app migrate -- --interactive=0
