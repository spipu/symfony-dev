#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh
source ./architecture/conf/env_lxd.sh

cd ./architecture/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

lxd-remove
lxd-deploy
echo ""

cd - > /dev/null

source ./architecture/create-abstract.sh
