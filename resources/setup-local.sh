#!/bin/bash
echo "====================================================================="
echo ""
echo " Setup Project Dependencies"
echo ""
echo "====================================================================="
echo ""

cd ..
composer global require phpmd/phpmd
composer install
yarn

if [[ ! -f .env ]]; then
  cp .env.sample .env
  echo 'Edit the .env file'
fi
