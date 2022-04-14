PATH_COMPOSER=/var/www/app.dev
PATH=/var/www/app.dev

autoload:
	docker-compose exec -w $(PATH_COMPOSER) php composer dumpautoload -o

composer-install:
	docker-compose exec -w $(PATH_COMPOSER) php composer install

laravel-key:
	docker-compose exec -w $(PATH) php php artisan key:generate

laravel-run-all-migrations:
	docker-compose exec -w $(PATH) php php artisan migrate
