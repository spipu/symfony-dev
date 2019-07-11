#!/bin/bash

cd "$( dirname "${BASH_SOURCE[0]}" )"
cd ../

source ./architecture/conf/env.sh

ENV_USER="delivery"

ENV_IP=`getent hosts ${ENV_HOST} | awk '{ print $1 }'`

cd ./architecture/vm/

HOUR=$(date +%H:%M:%S)
echo "[${HOUR}]===[DOCKER]==="
ssh-keygen -R "${ENV_HOST}"
ssh-keygen -R "${ENV_IP}"

echo " => Prepare /etc/hosts file"
sudo sed "/${ENV_HOST}/d" -i /etc/hosts
echo "# Added for docker ${ENV_HOST}" | sudo tee -a /etc/hosts > /dev/null
echo "127.0.50.1 ${ENV_HOST}"         | sudo tee -a /etc/hosts > /dev/null

echo " => Docker"
sudo docker-compose down -v
sudo docker-compose build --build-arg ssh_pub_key="$(cat ~/.ssh/id_rsa.pub)" webapp
sudo docker-compose up -d

cd - > /dev/null

sleep 2
ENV_IP=""
while [ ! "$ENV_IP" ] ; do
    ENV_IP=`getent hosts ${ENV_HOST} | awk '{ print $1 }'`
done
echo "  => $ENV_IP"
ssh-keygen -R "${ENV_HOST}" > /dev/null 2>&1
ssh-keygen -R "${ENV_IP}"   > /dev/null 2>&1
ssh-keyscan ${ENV_HOST}     >> ~/.ssh/known_hosts 2> /dev/null
ssh-keyscan ${ENV_IP}       >> ~/.ssh/known_hosts 2> /dev/null
echo ""

