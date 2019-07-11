#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh

ENV_USER="delivery"

cd ./architecture/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[LXD]==="
lxd-remove
lxd-deploy
echo ""

cd - > /dev/null

source ./architecture/full-init.sh
