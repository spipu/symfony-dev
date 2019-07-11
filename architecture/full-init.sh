#!/bin/bash

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[CLEAN]==="
sudo rm -rf ./src/var
sudo rm -rf ./src/cache
echo ""

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[PROVISION]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/provision.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[PERMISSION]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/permissions.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[CREATE DATABASE]==="
ssh root@${ENV_HOST} $ENV_FOLDER/architecture/scripts/createDb.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[INSTALL]==="
ssh ${ENV_USER}@${ENV_HOST} $ENV_FOLDER/architecture/scripts/install.sh

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[FINISHED]==="
