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
export XDEBUG_MODE=coverage

# Tests - PhpUnit
./bin/phpunit -c ./.phpunit.xml

# Clean temporary Key Pair
APP_ENCRYPTOR_KEY_PAIR=""
export APP_ENCRYPTOR_KEY_PAIR

# Output
firefox "${LOG_FOLDER}coverage/index.html"
