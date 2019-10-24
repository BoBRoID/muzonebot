#!/usr/bin/env bash

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Restart web-stack"
setenforce 0
service php-fpm restart
service nginx restart
service mysql restart

chmod 0777 /var/run/php-fpm/php-fpm.sock
chmod 0777 /var/lib/php/session -R