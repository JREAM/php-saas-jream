#!/bin/sh

echo "==========================================================="
echo " ( + ) Make sure to run ./utils.sh and testdb for testing."
echo "==========================================================="


cd tests
../vendor/phpunit/phpunit/phpunit .

