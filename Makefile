install:
	composer install
	cp ./src/config.php.default ./src/config.php -n
	chmod 0755 ./logs
test:
	composer run-script phpunit
lint:
	composer run-script phpcs -- --standard=PSR2 src public
run:
	php -S localhost:8000   -t ./public/
