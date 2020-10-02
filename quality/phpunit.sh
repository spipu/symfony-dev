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
./bin/phpunit -c ./.phpunit.xml

# Output
firefox "${LOG_FOLDER}coverage/index.html"
