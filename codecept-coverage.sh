#!/bin/bash

#echo "==========================================================="
#echo " ( + ) Make sure to run ./utils.sh and testdb for testing."
#echo "==========================================================="

# Create New:
# codecept g:cest acceptance User


#echo "XDebug is needed to generate coverage reports"
./vendor/bin/codecept run --coverage --coverage-html -vvv

#codecept run --steps
#codecept run --steps --coverage
