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
    rmcache     Removes Cache
    rmcachet    Remove Cache every 5 seconds (Infinite)
    q           Quit (or CTRL + C)
command_list

echo ""
echo "====================================================================="
echo ""

read -p "Type a Command: " cmd

    case $cmd in
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
