#!/bin/bash

set -e
bashSource=$(readlink -f "${BASH_SOURCE[0]}")
cd "$(dirname "$bashSource")"
cd ../

ENV_TYPE="none"
ENV_DO_NOT_GENERATE="yes"
source ./architecture/scripts/include/init.sh

MAIN_FOLDER="./${WEB_FOLDER}/src/Spipu"
BASE_GIT="git@github.com:spipu/symfony-bundle"

function setBundle() {
    code="$1"
    folder="$2"

    echo "Bundle [$code]"
    if [[ ! -d "${MAIN_FOLDER}/${folder}" ]];then
        echo "  => Git Clone"
        git clone "${BASE_GIT}-${code}.git" "${MAIN_FOLDER}/${folder}"
    else
        echo "  => Git Pull"
        cd "${MAIN_FOLDER}/${folder}"
        git pull
        git fetch
        cd - > /dev/null
    fi
    echo ""
}

mkdir -p "${MAIN_FOLDER}"

setBundle "configuration" "ConfigurationBundle"
setBundle "core"          "CoreBundle"
setBundle "process"       "ProcessBundle"
setBundle "ui"            "UiBundle"
setBundle "user"          "UserBundle"
