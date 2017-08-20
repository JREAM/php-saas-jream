#!/bin/bash
echo "====================================================================="
echo ""
echo "                        JREAM - Utils                      "
echo ""
echo " * To exit at anytime press CTRL+C"
echo ""
echo "====================================================================="
echo ""

CACHE_PATH=$PWD/cache/

while true; do
    cat <<- command_list
    CMD         PROCESS
    ----        --------------------------------
    phpcs       PHP Codesniffer (based on phpcs.xml)
    phpcbf      PHP Codesniffer Fixer (Fixes based on phpcs.xml)
    rmcache     Removes Cache
    rmcachet    Remove Cache every 5 seconds (Infinite)
    testdb      Creates a Test Database (jream_unit_test)
    devtools    N/A: Setup Phalcon Devtools to /opt/phalcon-tools
    q           Quit (or CTRL + C)
command_list

echo ""
echo "====================================================================="
echo ""

read -p "Type a Command: " cmd

    case $cmd in
        phpcs)
          echo "( + ) Running PHP Code Sniffer"
          ./vendor/bin/phpcs
          echo "( + ) Finished"
          echo ""
          echo "====================================================================="
          echo ""
        ;;
        phpcbf)
          echo "( + ) Running PHP Code Sniffer Formatter"
          ./vendor/bin/phpcbf
          echo "( + ) Finished"
          echo ""
          echo "====================================================================="
          echo ""
        ;;
        rmcache)
            echo "( + ) Flushing Redis.."
            redis-cli flushall
            echo "( + ) Removing Recursive Cache.."
            find $CACHE_PATH -type f -name '*.volt.php' -delete
            find $CACHE_PATH -type f -name '*.volt_e_.php' -delete
            echo "( + ) Removing Flat Cache.."
            rm -rf $CACHE_PATH*%%.php
            rm -rf $CACHE_PATH*.volt.php
            echo "( + ) Finished"
            echo ""
            echo "====================================================================="
            echo ""
            ;;
        rmcachet)
            while :
            do
                echo "( + ) Flushing Redis.."
                redis-cli flushall
                echo "( + ) Removing Recursive Cache.."
                find $CACHE_PATH -type f -name '*.volt.php' -delete
                find $CACHE_PATH -type f -name '*.volt_e_.php' -delete
                echo "( + ) Removing Flat Cache.."
                rm -rf $CACHE_PATH*%%.php
                rm -rf $CACHE_PATH*.volt.php
                echo "( + ) Finished"
                sleep 5
            done
            echo ""
            echo "====================================================================="
            echo ""
            ;;
        testdb)
          echo "( + ) Dumping 'jream' database to SQL [/tmp/jream.sql]"
          mysqldump -u root -proot jream > /tmp/jream.sql
          echo "( + ) Recreating 'jream_unit_test' database"
          mysqladmin drop -u root -proot jream_unit_test
          mysqladmin create -u root -proot jream_unit_test
          echo "( + ) Recreating 'jream_unit_test' database"
          mysql -u root -proot jream_unit_test < /tmp/jream.sql
          echo "( + ) Removing [/tmp/jream.sql] file"
          rm /tmp/jream.sql
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
