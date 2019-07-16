#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh
source ./architecture/conf/env_docker.sh

cd ./architecture/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[${ENV_TYPE}]==="

sudo docker-compose up -d

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[Finish]==="
