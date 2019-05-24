#!/usr/bin/env bash

chmod -R 0777 var
docker-compose build
./start.sh
./docker/bin/composer install
./docker/bin/install
./docker/bin/codeception build
