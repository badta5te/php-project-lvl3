start:
	heroku local -f Procfile.dev

setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm install

serve:
	php artisan serve

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku

lint:
	composer phpcs -- --standard=PSR12 app routes tests
	# composer phpstan -- --memory-limit=2G --level=8 analyse app/Http/Controllers/ routes/web.php tests

lint-fix:
	composer phpcbf
