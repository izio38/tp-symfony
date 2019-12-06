#!/bin/sh

docker run --rm --name=db_tp_php -p 3306:3306 -v /home/docker/mysql-projet-php-data:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=pass -d mysql:5.7
