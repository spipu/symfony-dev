#!/bin/bash

ENV_NAME="symfonydev"
ENV_HOST="${ENV_NAME}.lxc"
ENV_SSH_PORT="22"
ENV_USER="delivery"
ENV_FOLDER="/var/www/$ENV_NAME"
WEB_FOLDER="website"

if [[ -z "${ENV_TYPE}" ]]; then
    echo "ERROR - You must provide an Environment Type"
    exit 1
fi

if [[ "${ENV_TYPE}" != "none" ]]; then
    ENV_FILE="./architecture/conf/env_${ENV_TYPE}.sh"
    if [[ ! -f "${ENV_FILE}" ]]; then
        echo "ERROR - The Environment File \"${ENV_FILE}\" does not exist"
        exit 1
    fi

    source ${ENV_FILE}

    if [[ -z "${ENV_DO_NOT_GENERATE}" ]]; then
        rm -rf ./architecture/vm
        cp -r  ./architecture/conf/dev/vm ./architecture/vm

        FILES=`find ./architecture/vm/ -type f`
        for FILE in ${FILES}
        do
            sed -i "s/{{ENV_NAME}}/${ENV_NAME}/" ${FILE}
        done
    fi
fi
