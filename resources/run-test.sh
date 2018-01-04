#!/bin/bash

# Create New:
# ---------------------------------
# codecept g:cest acceptance User

# Run with Steps
# ---------------------------------
# codecept run --steps
# codecept run --steps --coverage
cd ..
while true; do
    cat <<- command_list
    CMD         PROCESS
    ----        --------------------------------
    c           Create Coverate Report
    f           Run Functional Tests
    u           Run Unit Tests
    q           Quit (or CTRL + C)
    ---
    Note: XDebug is needed for Coverage Reports
command_list


echo ""
echo "====================================================================="
echo ""

read -p "Type a Command: " cmd

    case $cmd in
        c|cov|coverage)
          echo "( + ) Running Codeception Coverage Report"
          #echo "XDebug is needed to generate coverage reports"
          ./vendor/bin/codecept run --coverage --coverage-html -vvv
          echo "( + ) Finished"
          echo ""
          echo "====================================================================="
          echo ""
        ;;
        f|func|functional)
          echo "( + ) Running Codeception Functional Tests"
          #echo "XDebug is needed to generate coverage reports"
          ./vendor/bin/codecept run functional -vvv
          echo "( + ) Finished"
          echo ""
          echo "====================================================================="
          echo ""
        ;;
        u|unit)
          echo "( + ) Running Codeception Unit Tests"
          #echo "XDebug is needed to generate coverage reports"
          ./vendor/bin/codecept run unit -vvv
          echo "( + ) Finished"
          echo ""
          echo "====================================================================="
          echo ""
        ;;
        q)
            exit 1
            ;;
        *)
            echo ""
            echo "    (!) OOPS! You typed a command that's not available."
            echo ""
            echo "====================================================================="
            echo ""

    esac

done
