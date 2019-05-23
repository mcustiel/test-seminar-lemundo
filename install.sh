#!/usr/bin/env bash

docker-compose build
./start.sh
./docker/bin/composer install
./docker/bin/install
./docker/bin/codeception build

