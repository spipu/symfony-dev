#!/bin/bash

showMessage " > Delivery User"

if [[ ! -d "/home/$ENV_USER" ]]; then
    useradd -g www-data -s /bin/bash -m $ENV_USER

    sed -i "s/#force_color_prompt/force_color_prompt/g" /home/$ENV_USER/.bashrc
    sed -i "s/#alias/alias/g" /home/$ENV_USER/.bashrc

    mkdir -p /home/$ENV_USER/.ssh/
    cp /home/ubuntu/.ssh/authorized_keys /home/$ENV_USER/.ssh/authorized_keys
    chmod 700 /home/$ENV_USER/.ssh
    chmod 600 /home/$ENV_USER/.ssh/authorized_keys
    chown -R $ENV_USER.www-data /home/$ENV_USER/.ssh

    mkdir -p /var/www
    chown -R $ENV_USER.root /var/www
    chmod -R 644 /var/www
    chmod -R +X /var/www

    mkdir -p /etc/sudoers.d/
    sh -c "echo 'Runas_Alias SERVERACCOUNTS=www-data'          >  /etc/sudoers.d/$ENV_USER"
    sh -c "echo '$ENV_USER ALL=(SERVERACCOUNTS) NOPASSWD: ALL' >> /etc/sudoers.d/$ENV_USER"
    chmod 440 /etc/sudoers.d/$ENV_USER
    chown root.root /etc/sudoers.d/$ENV_USER
fi
