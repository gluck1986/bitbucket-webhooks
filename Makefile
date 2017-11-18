install:
	composer install
	cp ./src/config.php.default ./src/config.php -f
test:
	composer run-script phpunit
lint:
	composer run-script phpcs -- --standard=PSR2 src public
