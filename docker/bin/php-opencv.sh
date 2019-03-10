#!/bin/bash

cd /usr/src

git clone https://github.com/php-opencv/php-opencv.git && cd php-opencv

phpize
./configure --with-php-config=/usr/bin/php-config7.1

make
make install
make test
