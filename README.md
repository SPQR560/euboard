docker-compose up -d
composer install
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load -n
npm install
npm run dev
php -S localhost:8000 -t public/

//test
APP_ENV=test php bin/console doctrine:migrations:migrate
APP_ENV=test php bin/console doctrine:fixtures:load -n
php bin/phpunit