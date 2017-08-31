#!/bin/bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php7.0

sudo apt install -y \
  php-apcu \
  php-apcu-bc \
  php-geoip \
  php-igbinary \
  php-imagick \
  php-mongodb \
  php-redis \
  php-solr \
  php-ssh2 \
  php-uuid \
  php-zmq \
  php-http \
  php-yaml \
  php-memcached \
  php-gearman \
  php7.1-cli \
  php7.1-dev \
  php7.1-phpdbg \
  php7.1-bcmath \
  php7.1-bz2 \
  php7.1-common \
  php7.1-curl \
  php7.1-gd \
  php7.1-imap \
  php7.1-intl \
  php7.1-json \
  php7.1-ldap \
  php7.1-mbstring \
  php7.1-mcrypt \
  php7.1-mysql \
  php7.1-odbc \
  php7.1-pgsql \
  php7.1-pspell \
  php7.1-readline \
  php7.1-recode \
  php7.1-snmp \
  php7.1-soap \
  php7.1-sqlite3 \
  php7.1-tidy \
  php7.1-xml \
  php7.1-xmlrpc \
  php7.1-sass \
  php7.1-zip \
  php7.1-opcache \
  php7.1-phalcon \
  php7.1-phalcon-dbgsym\
  libapache-mod-php7.1

sudo a2dismod php7.0
sudo a2enmod php7.1
sudo service apache2 reload

sudo apt purge php5*
sudo apt remove php7.0*

# Ensure PHP7.1 is set
sudo rm /etc/alternatives/php
ln -s /usr/bin/php7.1 /etc/alternatives/php


