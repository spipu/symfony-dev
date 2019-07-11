#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../website/

LOG_FOLDER="../quality/build/"

rm -rf ./var-test/

./vendor/bin/phpunit -c ./phpunit.xml

echo ""
echo "Coverage Report: ./quality/build/coverage/index.html"
echo ""
