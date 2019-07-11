#!/bin/bash

echo " > Install maildev"
npm install maildev@0.14.0 --global > /dev/null

NPM_PREFIX=$(npm config get prefix)
MAILDEV_BIN="${NPM_PREFIX}/bin/maildev"
MAILDEV_BIN=$(echo $MAILDEV_BIN | sed -e 's/[]\/&$*.^[]/\\&/g')

rm -f /etc/systemd/system/maildev.service
cp $CONFIG_FOLDER/maildev.service /etc/systemd/system/maildev.service
chown root.root /etc/systemd/system/maildev.service
chmod 644 /etc/systemd/system/maildev.service
sed -i "s/{{MAILDEV_BIN}}/$MAILDEV_BIN/g" /etc/systemd/system/maildev.service

systemctl enable maildev
systemctl start maildev

apt-get -qq install -y ssmtp > /dev/null

rm -f /etc/ssmtp/ssmtp.conf
cp $CONFIG_FOLDER/ssmtp.conf /etc/ssmtp/ssmtp.conf
chown root.root /etc/ssmtp/ssmtp.conf
chmod 644 /etc/ssmtp/ssmtp.conf
sed -i "s/{{ENV_HOST}}/$ENV_HOST/g" /etc/ssmtp/ssmtp.conf
