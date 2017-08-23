#!/bin/bash


if (php -i |grep xdebug\ support.* & -ne 'xdebug support => enabled')
then
  echo "Error: Xdebug must be enabled for Coverage Reports"
   exit
fi

echo "XDebug is needed to generate coverage reports"
./vendor/bin/codecept run unit -vvv
