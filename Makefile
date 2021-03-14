tests:
	php bin/console doctrine:fixtures:load --env=test -n
	symfony php bin/phpunit

composer:
	docker-compose run manager-php-cli composer

test:
	docker-compose run --rm manager-php-cli php bin/phpunit

make-migration:
	docker-compose run --rm manager-php-cli php bin/console make:migration

migrate-migration:
	docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate
	docker-compose run --rm manager-php-cli APP_ENV=test php bin/console doctrine:migrations:migrate

migrate-migration-win:
	docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate
	docker-compose run --rm manager-php-cli php bin/console doctrine:migrations:migrate --env=test

load-fixtures:
	docker-compose run --rm manager-php-cli php bin/console doctrine:fixtures:load
	docker-compose run --rm manager-php-cli APP_ENV=test php bin/console doctrine:fixtures:load

load-fixtures-win:
	docker-compose run --rm manager-php-cli php bin/console doctrine:fixtures:load
	docker-compose run --rm manager-php-cli php bin/console doctrine:fixtures:load --env=test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build