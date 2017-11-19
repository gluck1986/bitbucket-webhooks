# bitbucket-webhooks

[![Build Status](https://travis-ci.org/gluck1986/bitbucket-webhooks.svg?branch=master)](https://travis-ci.org/gluck1986/bitbucket-webhooks)

Обработка хуков bitbucket
Заточено чисто под хуки смерженых пул реквестов.

Установка:
make install.

Настройка:
-отредактировать config.php под свои нужды

запуск локального сервера:
-make run

для локальной отладки можно воспользоваться таким методом:

curl -X POST -d @json.json http://localhost:8000
где   @json.json  файл с телом json ожидаемого хука. 
