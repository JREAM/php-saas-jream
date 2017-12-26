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
ACL_DATA_FILE=$PWD/app/security/acl.data

while true; do
    cat <<- command_list
    CMD         PROCESS
    ----        --------------------------------
    writable    Make Cache/Security Writable By Group with Permissions (NOT 777)
    phpcs       PHP Codesniffer (based on phpcs.xml)
    phpcbf      PHP Codesniffer Fixer (Fixes based on phpcs.xml)
    rmcache     Removes Cache
    rmcachet    Remove Cache every 5 seconds (Infinite)
    db          Import the schema.sql into Database (jream)
    testdb      Creates a Test Database (jream_unit_test)
    devtools    N/A: Setup Phalcon Devtools to /opt/phalcon-tools

    rmusr      Removes the fake testing account on a timer when doing api calls every 10 secs

    q           Quit (or CTRL + C)
command_list

echo ""
echo "====================================================================="
echo ""

read -p "Type a Command: " cmd

    case $cmd in
        phpcs)
          echo "( + ) Running PHP Code Sniffer"
          ./vendor/bin/phpcsW
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
            echo "( + ) Removing ACL Data File.."
            rm $ACL_DATA_FILE
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
                echo "( + ) Removing ACL Data File.."
                rm $ACL_DATA_FILE
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
        rmusr)
            USER_ID=$(mysql -uroot -proot jream -e "SELECT id FROM user WHERE email = 'testbot01@jream-ignore.com'" | grep -o '[0-9]*');
            while true; do
              mysql -uroot -proot jream -e "DELETE FROM \`user\` WHERE \`alias\` = \"testbotone\" OR \"email\"='testbot01@jream-ignore.com'"
              mysql -uroot -proot jream -e "DELETE FROM newsletter_subscription WHERE \`email\` = \"testbot01@jream-ignore.com\""
              if [ -z $USER_ID ]; then
                mysql -uroot -proot jream -e "DELETE FROM user_purchase  WHERE \`user_id\` = \"${USER_ID}\""
                mysql -uroot -proot jream -e "DELETE FROM transaction  WHERE \`user_id\` = \"${USER_ID}\""
                USER_ID=''
              fi
              sleep 10
            done
            ;;
        db)
          echo "( + ) Dropping, and Re-creating Database (jream)"
          mysqladmin drop -u root -proot jream
          mysqladmin create -u root -proot jream
          echo "( + ) Importing __setup/schema.sql into (jream)"
          mysql -u root -proot jream < resources/__setup/schema.sql
          echo "( + ) Done."
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
        writable)
          echo "( + ) Setting ./cache & ./app/security to group: www-data"
          sudo chgrp -R www-data ./cache
          sudo chgrp -R www-data ./app/security
          echo "( + ) Settings ./cache & ./app/security to chmod g+rw"
          sudo chmod -R g+rw ./cache
          sudo chmod -R g+rw ./app/security
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
