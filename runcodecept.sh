#!/bin/sh

#echo "==========================================================="
#echo " ( + ) Make sure to run ./utils.sh and testdb for testing."
#echo "==========================================================="

# Create New:
# codecept g:cest acceptance User

codecept run --steps


#codecept run --steps --coverage