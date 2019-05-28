#!/usr/bin/env bash

function writemsg {
    echo -e "\e[92m$1"
    echo -n -e "\e[0m"
}

echo
writemsg "Running unit tests..."
./unit.sh
echo
writemsg "Running integration tests..."
./integration.sh
echo
writemsg "Running acceptance tests..."
./acceptance.sh
