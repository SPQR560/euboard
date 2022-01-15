tests:
	php bin/console doctrine:fixtures:load --env=test -n
	php bin/phpunit

composer:
	docker-compose run php-cli composer

test:
	docker-compose run --rm php-cli php bin/phpunit

make-migration:
	docker-compose run --rm php-cli php bin/console make:migration

migrate-migration:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate
	docker-compose run --rm php-cli APP_ENV=test php bin/console doctrine:migrations:migrate

migrate-migration-win:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate -n
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --env=test -n

load-fixtures:
	docker-compose run --rm php-cli php bin/console doctrine:fixtures:load -n
	docker-compose run --rm php-cli APP_ENV=test php bin/console doctrine:fixtures:load -n

load-fixtures-win:
	docker-compose run --rm php-cli php bin/console doctrine:fixtures:load -n
	docker-compose run --rm php-cli php bin/console doctrine:fixtures:load --env=test -n

install-assets:
	docker-compose run --rm node npm run dev

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build

remove-old-threads:
	php bin/console app:delete-old-treads