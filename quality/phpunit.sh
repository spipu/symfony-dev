#!/bin/bash

# Go into the project folder
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../website/

# Create the build folder
LOG_FOLDER="../quality/build/"
mkdir -p $LOG_FOLDER

# Cleanup
rm -rf ./var-test

# Tests - PhpUnit
export APP_ENCRYPTOR_KEY_PAIR="x/Pd7jf0DwDnoLBQqqX15cSyNsP777YDDow662IihulwvaBIsSjT3/qR6+JR2glrXh5jQQJq6Ex9OolQcFFdIQ=="
./bin/phpunit -c ./.phpunit.xml
export APP_ENCRYPTOR_KEY_PAIR=""

# Output
firefox "${LOG_FOLDER}coverage/index.html"
