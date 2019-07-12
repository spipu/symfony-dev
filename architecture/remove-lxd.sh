#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh
source ./architecture/conf/env_lxd.sh

ENV_IP=`getent hosts ${ENV_HOST} | awk '{ print $1 }'`

cd ./architecture/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

lxd-remove

cd - > /dev/null
echo ""
