#!/bin/bash
echo "====================================================================="
echo ""
echo " Setup Project Dependencies"
echo ""
echo "====================================================================="
echo ""

composer global require phpmd/phpmd
composer install
npm i
if [[ ! -f .env ]]; then
  cp .env.sample .env
  echo 'Edit the .env file'
fi
