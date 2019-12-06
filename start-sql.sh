#!/bin/sh

docker run --rm --name db_symfo -p 3306:3306 -e MYSQL_ROOT_PASSWORD=pass -d mysql:5.7
