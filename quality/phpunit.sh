#!/bin/bash

# Go into the project folder
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../website/

# Create the build folder
LOG_FOLDER="../quality/build/"
mkdir -p $LOG_FOLDER

# Cleanup
rm -rf ./var-test

# Create temporary Key Pair
APP_ENCRYPTOR_KEY_PAIR=$(php -r "echo sodium_bin2base64(sodium_crypto_box_keypair(), SODIUM_BASE64_VARIANT_ORIGINAL);")
export APP_ENCRYPTOR_KEY_PAIR
APP_ENV="dev"
export APP_ENV

# Increase max open file limit
ulimit -n 8192

# Tests - PhpUnit --no-coverage
if [[ "$1" == "--coverage" ]]; then
  XDEBUG_MODE="coverage"
  export XDEBUG_MODE
  echo "PHPUnit - With Coverage"
  php8.1 ./bin/phpunit -c ./.phpunit.xml $2
  XDEBUG_MODE=""
  export XDEBUG_MODE
else
  echo "PHPUnit - Without Coverage"
  php8.1 ./bin/phpunit -c ./.phpunit.xml --no-coverage $1
fi

# Clean bad cache
rm -rf ./var/log
rm -rf ./var/cache

# Clean temporary Key Pair
APP_ENCRYPTOR_KEY_PAIR=""
export APP_ENCRYPTOR_KEY_PAIR

APP_ENV=""
export APP_ENV

# Output
if [[ "$1" == "--coverage" ]]; then
  firefox "${LOG_FOLDER}coverage/index.html"
fi
