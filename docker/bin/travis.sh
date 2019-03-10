#!/bin/bash

cd /var/www

git clone https://github.com/ankitpokhrel/alt.git && cd alt

composer install

./vendor/bin/phpunit --coverage-text
