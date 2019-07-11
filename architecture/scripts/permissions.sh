#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../../

source ./architecture/conf/env.sh

MAIN_FOLDER="$ENV_FOLDER/website"

mkdir -p ${MAIN_FOLDER}/var
chown -R www-data.www-data ${MAIN_FOLDER}/var
chmod -R 664 ${MAIN_FOLDER}/var
chmod -R +X ${MAIN_FOLDER}/var

chmod +X ${MAIN_FOLDER}/bin/*

mkdir -p ${MAIN_FOLDER}/public/media/config
chown -R www-data.www-data ${MAIN_FOLDER}/public/media/config
chmod -R 664 ${MAIN_FOLDER}/public/media/config
chmod -R +X ${MAIN_FOLDER}/public/media/config
