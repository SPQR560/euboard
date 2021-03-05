tests:
	php bin/console doctrine:fixtures:load --env=test -n
	symfony php bin/phpunit

composer:
	docker-compose run manager-php-cli composer

test:
	docker-compose run --rm manager-php-cli php bin/phpunit