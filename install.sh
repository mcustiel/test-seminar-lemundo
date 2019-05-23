#!/usr/bin/env bash

docker-compose build
docker-compose up -d
./docker/bin/composer install
./docker/bin/install
./docker/bin/codeception build

