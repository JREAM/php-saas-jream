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

echo "Setting Up Phalcon Devtools"
cd ~
git clone https://github.com/phalcon/phalcon-devtools.git
cd phalcon-devtools
sudo ln -s $(pwd)/phalcon.php /usr/bin/phalcon
sudo chmod ugo+x /usr/bin/phalcon
echo "Done, you should be able to run $ phalcon"
