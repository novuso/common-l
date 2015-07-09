#!/usr/bin/env bash

# variables
PROJECT_ROOT=$1

# upgrade
sudo apt-get update
sudo apt-get install -y aptitude
sudo aptitude -y safe-upgrade

# base
sudo apt-get install -y git vim curl openssl unzip software-properties-common ntp
sudo apt-get install -y build-essential autoconf automake libtool bison re2c
sudo apt-get install -y libxml2-dev libcurl4-openssl-dev libicu-dev

# directories
mkdir $HOME/bin
mkdir $HOME/php
mkdir $HOME/php.d

# compile php
cd $HOME
git clone https://github.com/php/php-src.git $HOME/php/src
cd $HOME/php/src
./buildconf
./configure \
    --prefix=$HOME/php \
    --with-config-file-scan-dir=$HOME/php.d \
    --enable-debug \
    --enable-maintainer-zts \
    --enable-mbstring \
    --enable-zip \
    --enable-json \
    --enable-phar \
    --enable-filter \
    --enable-hash \
    --enable-intl \
    --enable-ctype \
    --enable-dom \
    --enable-tokenizer \
    --enable-libxml \
    --enable-simplexml \
    --with-openssl \
    --with-pdo-mysql \
    --with-curl=/usr/bin
make
make install

# add php to path
cd $HOME
echo '' | tee -a $HOME/.bashrc
echo 'export PATH="$PATH:$HOME/php/bin"' | tee -a $HOME/.bashrc
echo '' | tee -a $HOME/.bashrc
source $HOME/.bashrc

# configuration
cp $HOME/php/src/php.ini-development $HOME/php/lib/php.ini
sed -i "s/^;date\.timezone.*/date.timezone = UTC/" $HOME/php/lib/php.ini
sed -i "s/^zend\.assertions.*/zend.assertions = 1/" $HOME/php/lib/php.ini
sed -i "s/^;assert\.active.*/assert.active = On/" $HOME/php/lib/php.ini
sed -i "s/^;assert\.exception.*/assert.exception = On/" $HOME/php/lib/php.ini
echo '' | tee -a $HOME/php/lib/php.ini

# composer
cd ~
curl -sS https://getcomposer.org/installer | $HOME/php/bin/php
sudo mv composer.phar /usr/local/bin/composer

# copy scripts to bin folder
cp $PROJECT_ROOT/app/script/assert-disable.sh $HOME/bin/assert-disable && sudo chmod 0700 $HOME/bin/assert-disable
cp $PROJECT_ROOT/app/script/assert-enable.sh $HOME/bin/assert-enable && sudo chmod 0700 $HOME/bin/assert-enable
