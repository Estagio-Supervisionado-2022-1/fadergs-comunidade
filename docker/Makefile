MYPATH=/var/www/app.dev

autoload:
	docker-compose exec -w $(MYPATH) php composer dumpautoload -o

composer-install:
	docker-compose exec -w $(MYPATH) php composer install

laravel-key:
	docker-compose exec -w $(MYPATH) php php artisan key:generate

laravel-run-all-migrations:
	docker-compose exec -w $(MYPATH) php php artisan migrate

laravel-artisan-serve:
	docker-compose exec -w /var/www/app.dev/ php php artisan serve --host 0.0.0.0
