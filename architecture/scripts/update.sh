#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

source ./architecture/conf/env.sh

cd ${ENV_FOLDER}/website
composer install
./bin/console doctrine:schema:update --force
./bin/console assets:install --no-interaction
./bin/console spipu:assets:install --no-interaction
rm -rf ./var/cache/*
