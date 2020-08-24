init: docker-down-clear \
	  api-clear \
	  docker-pull docker-build docker-up \
	  api-init

up: docker-up
down: docker-down
restart: down up
test: api-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* public/docs/*'

api-init: api-permissions api-composer-install api-wait-db app-migrations api-generate-documentation

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-db:5432 -t 30

api-test:
	docker-compose run --rm api-php-cli php bin/phpunit

app-migrations:
	docker-compose run --rm api-php-cli php bin/console doctrine:migrations:migrate --no-interaction

api-generate-documentation:
	docker-compose run --rm api-php-cli php bin/console api:generate-docs

api-fixtures-test:
	docker-compose run --rm api-php-cli php bin/console doctrine:fixtures:load --no-interaction --env=test
