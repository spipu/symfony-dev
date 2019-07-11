#!/bin/bash

echo " > Install MySQL"
apt-get -qq -y install mysql-server > /dev/null

echo " > Configure MySQL"
systemctl stop mysql  > /dev/null
ln -s $CONFIG_FOLDER/mysql.cnf /etc/mysql/conf.d/99-provision.cnf
systemctl start mysql > /dev/null
systemctl is-enabled mysql > /dev/null || systemctl enable mysql > /dev/null
systemctl status mysql > /dev/null
