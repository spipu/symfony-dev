#!/bin/bash

echo " > Install MySQL"
apt-get -qq -y install mysql-server > /dev/null

echo " > Configure MySQL"
/etc/init.d/mysql stop  > /dev/null
ln -s $CONFIG_FOLDER/mysql.cnf /etc/mysql/conf.d/99-provision.cnf
/etc/init.d/mysql start mysql > /dev/null
/etc/init.d/mysql status mysql > /dev/null
