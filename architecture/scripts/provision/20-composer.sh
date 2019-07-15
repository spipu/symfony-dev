#!/bin/bash

echo " > Install Composer"

wget -q https://getcomposer.org/composer.phar > /dev/null
mv ./composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
composer self-update -q > /dev/null