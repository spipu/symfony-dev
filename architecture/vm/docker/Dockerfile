FROM ubuntu:18.04

ARG ssh_pub_key

# Upgrade the VM
RUN apt-get -qq update
RUN export DEBIAN_FRONTEND=noninteractive && apt-get -qq -y install apt-utils                > /dev/null
RUN export DEBIAN_FRONTEND=noninteractive && apt-get -qq -y dist-upgrade                     > /dev/null
RUN export DEBIAN_FRONTEND=noninteractive && apt-get -qq -y autoremove                       > /dev/null
RUN export DEBIAN_FRONTEND=noninteractive && apt-get -qq -y install sudo net-tools vim       > /dev/null

# Install SSH Package
RUN export DEBIAN_FRONTEND=noninteractive && apt-get -qq -y install sudo ssh openssh-server  > /dev/null
RUN /etc/init.d/ssh stop

# Configure SSH
RUN sed -i "s/#Port 22/Port 22/g"      /etc/ssh/sshd_config
RUN sed -i "/PasswordAuthentication/d" /etc/ssh/sshd_config
RUN sed -i "/PermitRootLogin/d"        /etc/ssh/sshd_config
RUN echo ""                                 >> /etc/ssh/sshd_config
RUN echo "PasswordAuthentication no"        >> /etc/ssh/sshd_config
RUN echo "PermitRootLogin without-password" >> /etc/ssh/sshd_config
EXPOSE 22

# Add SSH Key
RUN mkdir -p /root/.ssh
RUN echo "$ssh_pub_key" > /root/.ssh/authorized_keys
RUN chmod 700 /root/.ssh
RUN chmod 600 /root/.ssh/authorized_keys
RUN chown -R root.root /root/.ssh

# Configure BASH
RUN sed -i "s/# export/export/g" /root/.bashrc
RUN sed -i "s/# alias/alias/g"   /root/.bashrc

# Configure User Delivery - CREATE
RUN groupadd -f www-data
RUN useradd -g www-data -s /bin/bash -u 1000 -m delivery

# Configure User Delivery - BASH
RUN sed -i "s/#force_color_prompt/force_color_prompt/g" /home/delivery/.bashrc
RUN sed -i "s/#alias/alias/g"                           /home/delivery/.bashrc

# Configure User Delivery - SSH KEY
RUN mkdir -p /home/delivery/.ssh/
RUN echo "$ssh_pub_key" > /home/delivery/.ssh/authorized_keys
RUN chmod 700 /home/delivery/.ssh
RUN chmod 600 /home/delivery/.ssh/authorized_keys
RUN chown -R delivery.www-data /home/delivery/.ssh

# Configure User Delivery - WWW FOLDER
RUN mkdir -p /var/www
RUN chown delivery.root /var/www
RUN chmod 755 /var/www

# Configure User Delivery - SUDOER
RUN mkdir -p /etc/sudoers.d/
RUN echo 'Runas_Alias SERVERACCOUNTS=www-data'         >  /etc/sudoers.d/delivery
RUN echo 'delivery ALL=(SERVERACCOUNTS) NOPASSWD: ALL' >> /etc/sudoers.d/delivery
RUN chmod 440 /etc/sudoers.d/delivery
RUN chown root.root /etc/sudoers.d/delivery

# Configure Local
RUN echo "#Locales"        >> /root/.bashrc
RUN echo "export LC_ALL=C" >> /root/.bashrc

RUN echo "#Locales"        >> /home/delivery/.bashrc
RUN echo "export LC_ALL=C" >> /home/delivery/.bashrc

# Restart Services
COPY start.sh /root/start.sh
RUN chmod 755 /root/start.sh
ENTRYPOINT /root/start.sh && bash
