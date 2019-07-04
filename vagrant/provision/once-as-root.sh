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

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Add MariaDB repo"
cat > /etc/yum.repos.d/MariaDB.repo <<EOL
# MariaDB 10.3 CentOS repository list - created 2019-01-10 13:23 UTC
# http://downloads.mariadb.org/mariadb/repositories/
[mariadb]
name = MariaDB
baseurl = http://yum.mariadb.org/10.3/centos7-amd64
gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
gpgcheck=1
EOL

info "Install remi-release 7"
yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm

info "update && disable php 5.4 && enable php 7.3"
yum -y update
yum-config-manager --disable remi-php54
yum-config-manager --enable remi-php73

info "Install yum utils"
yum -y install epel-release yum-utils

info "Update && upgrade"
yum -y update
yum -y upgrade

info "Install PHP 7.3"
yum -y install php-curl php-cli php-intl php-mysqlnd php-gd php-fpm php-mbstring php-xml unzip nginx php-bcmath

info "Install MariaDB"
yum -y install MariaDB-server expect

info "Configure MySQL"
service mysql restart
SECURE_MYSQL=$(expect -c "
set timeout 10
spawn mysql_secure_installation
expect \"Enter current password for root (enter for none):\"
send \"\r\"
expect \"Change the root password?\"
send \"n\r\"
expect \"Remove anonymous users?\"
send \"y\r\"
expect \"Disallow root login remotely?\"
send \"n\r\"
expect \"Remove test database and access to it?\"
send \"y\r\"
expect \"Reload privilege tables now?\"
send \"y\r\"
expect eof
")

echo "$SECURE_MYSQL"
yum -y remove expect

sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/my.cnf.d/server.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = apache/user = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/group = apache/group = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/owner = apache/owner = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm\/php-fpm.sock/g' /etc/php-fpm.d/www.conf
sed -i 's/;listen.owner = nobody/listen.owner = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/;listen.group = nobody/listen.group = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/listen.owner = nobody/listen.owner = vagrant/g' /etc/php-fpm.d/www.conf
sed -i 's/listen.group = nobody/listen.group = vagrant/g' /etc/php-fpm.d/www.conf
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/conf.d/app.conf
echo "Done!"

info "Disabling SElinux"
setenforce 0
sed -i 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/sysconfig/selinux

info "Change owners"
chown vagrant:vagrant /var/lib/nginx -R

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE muzone"
echo "Done!"

chmod 0777 /var/run/php-fpm/php-fpm.sock
chmod 0777 /var/lib/php/session -R
