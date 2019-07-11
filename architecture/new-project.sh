#!/usr/bin/env bash

PROJECT_NAME="symfonydev"
FOLDER_PROJECT="./website"
PROJECT_SECRET=$(echo "${PROJECT_NAME}_$(date +'%s')" | md5sum | cut -d' ' -f1)

composer create-project symfony/website-skeleton    ${FOLDER_PROJECT} --ignore-platform-reqs --no-install
composer require sensiolabs/security-checker     -d ${FOLDER_PROJECT} --no-update
composer remove symfony/web-server-bundle        -d ${FOLDER_PROJECT} --dev --no-update
composer config bin-dir "./bin"                  -d ${FOLDER_PROJECT}
composer config platform.php            "7.2.10" -d ${FOLDER_PROJECT}
composer config platform.ext-bcmath     "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-ctype      "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-gd         "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-spl        "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-dom        "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-simplexml  "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-mcrypt     "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-hash       "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-curl       "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-iconv      "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-intl       "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-xsl        "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-mbstring   "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-openssl    "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-zip        "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-pdo_mysql  "1"      -d ${FOLDER_PROJECT}
composer config platform.ext-soap       "1"      -d ${FOLDER_PROJECT}
composer config platform.lib-libxml     "1"      -d ${FOLDER_PROJECT}
composer install --no-interaction                -d ${FOLDER_PROJECT}
chmod +x ${FOLDER_PROJECT}/bin/*

FOLDER_CONFIG="${FOLDER_PROJECT}/config/"
FILE_CONFIG="${FOLDER_CONFIG}packages/framework.yaml"
mv ${FILE_CONFIG} ${FILE_CONFIG}.tmp
echo "imports:"                                                            >> ${FILE_CONFIG}
echo "    - { resource: ../app_configuration.yaml }"                       >> ${FILE_CONFIG}
echo ""                                                                    >> ${FILE_CONFIG}
cat ${FILE_CONFIG}.tmp >> ${FILE_CONFIG}
rm -f ${FILE_CONFIG}.tmp

FILE_CONFIG="${FOLDER_CONFIG}app_configuration.yaml"
echo "parameters:" >> ${FILE_CONFIG}
echo "    APP_SETTINGS_APP_ENV:             'dev'" >> ${FILE_CONFIG}
echo "    APP_SETTINGS_APP_SECRET:          '${PROJECT_SECRET}'" >> ${FILE_CONFIG}
echo "    APP_SETTINGS_CACHE_APP:           'cache.adapter.filesystem'" >> ${FILE_CONFIG}
echo "    APP_SETTINGS_DATABASE_URL:        'mysql://${PROJECT_NAME}:!${PROJECT_NAME}@localhost:3306/${PROJECT_NAME}'" >> ${FILE_CONFIG}
echo "    APP_SETTINGS_MAILER_URL:          'smtp://127.0.0.1:1025?encryption=&auth_mode='" >> ${FILE_CONFIG}

FILES=`find ${FOLDER_CONFIG}/ -type f | grep -v "debug.yaml"`
for FILE in ${FILES}
do
    sed -i "s/%env(\([^:]*\))%/%APP_SETTINGS_\1%/g" ${FILE}
    sed -i "s/%env([^:]*:*\([^:]*\))%/%APP_SETTINGS_\1%/g" ${FILE}
done

rm -f ${FOLDER_PROJECT}/.env
rm -f ${FOLDER_PROJECT}/.env.dist