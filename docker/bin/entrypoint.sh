#!/bin/bash

# Install tensorflow
pip3 install tensorflow

# Run composer.
composer self-update
composer install

# Run supervisord.
/usr/bin/supervisord -n -c /etc/supervisord.conf
