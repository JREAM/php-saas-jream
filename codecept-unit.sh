#!/bin/bash

echo "XDebug is needed to generate coverage reports"
./vendor/bin/codecept run unit -vvv
