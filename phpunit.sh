#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ./website/

rm -rf ./var-test/

./vendor/bin/phpunit -c ./phpunit.xml

echo ""
echo "Coverage Report: ./build/coverage/index.html"
echo ""
