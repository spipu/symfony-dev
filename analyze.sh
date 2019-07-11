#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ./website/

LOG_FOLDER="./build/"

rm -rf $LOG_FOLDER
mkdir -p $LOG_FOLDER

rm -rf ./var-test/

./vendor/bin/phpqa \
    --analyzedDirs src \
    --ignoredDirs vendor,Tests \
    --tools phpmetrics,phploc,phpcs,phpmd,pdepend,phpcpd,phpunit,parallel-lint \
    --config ./ \
    --buildDir $LOG_FOLDER \
    --report offline \
    --execution no-parallel

firefox "${LOG_FOLDER}phpqa.html"
firefox "${LOG_FOLDER}coverage/index.html"
