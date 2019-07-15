#!/bin/bash

echo " > Install Apache + PHP"

PHP_VERSION="7.2"
PHP_FOLDER="php/7.2"

apt-get -qq -y install apache2 libapache2-mod-php${PHP_VERSION} php${PHP_VERSION}-cli > /dev/null

apt-get -qq -y install \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-iconv \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-json \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-pdo \
    php${PHP_VERSION}-pdo-mysql \
    php${PHP_VERSION}-readline \
    php${PHP_VERSION}-simplexml \
    php${PHP_VERSION}-soap \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-xsl \
    php${PHP_VERSION}-zip \
    > /dev/null

apt-get -qq -y install libapache2-mod-xsendfile > /dev/null

echo " > Configure Apache + PHP"
a2enmod rewrite > /dev/null
a2enmod headers > /dev/null

echo "ServerName $ENV_HOST" >> /etc/apache2/apache2.conf

rm -f /etc/${PHP_FOLDER}/cli/conf.d/99-provision.ini
ln -s $CONFIG_FOLDER/php/cli.ini /etc/${PHP_FOLDER}/cli/conf.d/99-provision.ini

rm -f /etc/${PHP_FOLDER}/apache2/conf.d/99-provision.ini
ln -s $CONFIG_FOLDER/php/apache.ini /etc/${PHP_FOLDER}/apache2/conf.d/99-provision.ini

rm -f /etc/apache2/sites-available/*
cp $CONFIG_FOLDER/apache/virtualhost.conf /etc/apache2/sites-available/$ENV_NAME.conf
sed -i "s/{{ENV_NAME}}/$ENV_NAME/g"         /etc/apache2/sites-available/$ENV_NAME.conf
sed -i "s/{{ENV_HOST}}/$ENV_HOST/g"         /etc/apache2/sites-available/$ENV_NAME.conf
sed -i "s/{{ENV_FOLDER}}/$ENV_FOLDER_SED/g" /etc/apache2/sites-available/$ENV_NAME.conf

rm -f /etc/apache2/sites-enabled/*
ln -s /etc/apache2/sites-available/$ENV_NAME.conf /etc/apache2/sites-enabled/

echo " > Restart Apache"

if [[ "$ENV_TYPE" = "docker" ]]; then
    /etc/init.d/apache2 restart > /dev/null
else
    systemctl restart apache2 > /dev/null
fi

echo " > Symfony Configuration"

echo "export APP_ENV=dev" >> /etc/environment

mkdir -p /etc/symfony
rm -f /etc/symfony/$ENV_NAME.yaml
cp $CONFIG_FOLDER/symfony/app.yaml /etc/symfony/$ENV_NAME.yaml
sed -i "s/{{ENV_NAME}}/$ENV_NAME/g" /etc/symfony/$ENV_NAME.yaml

chmod 750 /etc/symfony
chmod 640 /etc/symfony/$ENV_NAME.yaml
chown root.www-data /etc/symfony
chown root.www-data /etc/symfony/$ENV_NAME.yaml
