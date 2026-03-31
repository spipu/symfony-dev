#!/bin/bash

set -e

bashSource=$(readlink -f "${BASH_SOURCE[0]}")
cd "$(dirname "$bashSource")"
cd ../../

ENV_TYPE="none"
ENV_DO_NOT_GENERATE="yes"
source ./architecture/scripts/include/init.sh

showTitle "Update"

cd "${ENV_FOLDER}/${WEB_FOLDER}"

showMessage "Composer"
composer update

showMessage "End"

../architecture/scripts/install.sh
