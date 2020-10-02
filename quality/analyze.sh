#!/bin/bash

# Go into the project folder
cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../website/

# Create the build folder
LOG_FOLDER="../quality/build/"
mkdir -p $LOG_FOLDER

# Tests - PHPQA
./vendor/bin/phpqa \
    --analyzedDirs src \
    --ignoredDirs vendor,Tests \
    --tools phpmetrics,phploc,pdepend,phpcs:0,phpmd:0,phpcpd:0,parallel-lint:0 \
    --config ./ \
    --buildDir $LOG_FOLDER \
    --report offline \
    --execution no-parallel

# Output
firefox "${LOG_FOLDER}phpqa.html"
