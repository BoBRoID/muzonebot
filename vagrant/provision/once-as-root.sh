#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Allocate swap for MySQL 5.6"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
update-locale LC_ALL="C"
dpkg-reconfigure locales

info "Configure timezone"
echo ${timezone} | tee /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

MYSQL_ROOT_PASSWORD=""

info "Prepare root password for MySQL"
debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password password password'
debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password_again password password'
echo "Done!"

info "Update OS software"
sudo apt install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php
apt-get update

info "Install additional software"
apt-get install -y git php7.1 php7.1-mbstring php7.1-curl php7.1-xml php7.1-intl php7.1-fpm php7.1-zip php7.1-mysql php7.1-cli php7.1-gd php7.1-json nginx mysql-server mysql-client --force-yes

info "Configure MySQL"
mysql -u root -ppassword -e "use mysql; UPDATE user SET authentication_string=PASSWORD('') WHERE User='root'; flush privileges;"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0\
\nsql-mode=\"\"/" /etc/mysql/mysql.conf.d/mysqld.cnf
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE USER 'root'@'%'"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION"
mysql -uroot <<< "CREATE DATABASE muzone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer