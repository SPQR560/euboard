init: docker-up composer-install migrate-migration load-fixtures npm-install install-assets

copy-env:
	cp .env.example .env 

composer-install:
	docker compose run php-cli composer install

test:
	docker compose run --rm php-cli php bin/phpunit

stan:
	docker compose run php-cli php vendor/bin/phpstan analyse -c phpstan.dist.neon

rector:
	docker compose run php-cli php vendor/bin/rector process src --dry-run	

rector-fix:
	docker compose run php-cli php vendor/bin/rector process src		

make-migration:
	docker compose run --rm php-cli php bin/console make:migration

migrate-migration:
	docker compose run --rm php-cli php bin/console doctrine:migrations:migrate
	docker compose run --rm php-cli php bin/console --env=test doctrine:migrations:migrate


load-fixtures:
	docker compose run --rm php-cli php bin/console doctrine:fixtures:load -n
	docker compose run --rm php-cli php bin/console --env=test doctrine:fixtures:load -n

npm-install:
	docker compose run --rm node npm install

install-assets:
	docker compose run --rm node npm run dev

docker-up:
	docker compose up -d --build --force-recreate

docker-down:
	docker compose down --remove-orphans

docker-down-v:
	docker compose down -v --remove-orphans	

docker-build:
	docker compose build

docker-hard-reset: docker-down-v docker-up

remove-old-threads:
	php bin/console app:delete-old-treads