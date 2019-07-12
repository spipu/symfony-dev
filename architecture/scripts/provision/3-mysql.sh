#!/bin/bash

echo " > Install MySQL"

apt-get -qq -y install mysql-server > /dev/null

echo " > Configure MySQL"
if [[ "$ENV_TYPE" = "docker" ]]; then
    /etc/init.d/mysql stop  > /dev/null
else
    systemctl stop mysql  > /dev/null
fi


ln -s $CONFIG_FOLDER/mysql.cnf /etc/mysql/conf.d/99-provision.cnf

if [[ "$ENV_TYPE" = "docker" ]]; then
    /etc/init.d/mysql start mysql > /dev/null
    /etc/init.d/mysql status mysql > /dev/null
else
    systemctl start mysql      > /dev/null
    systemctl is-enabled mysql > /dev/null || systemctl enable mysql > /dev/null
    systemctl status mysql     > /dev/null
fi
