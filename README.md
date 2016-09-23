# JREAM
(C) 2016 JREAM LLC.

JREAM (Jesus Rules Everything Around Me) is a website for streaming videos I've made over the years.
The system is built to serve people content on the fly after they purchase a product. Reliablity
is important since JREAM LLC provides a serve. To solve this, AWS is utilized for fall-back servers, Redundant Storage, Geolocalized CDN, and Multi A-Z DB.

## Technologies

Below are some of the technologies used to build this web application:

- MySQL InnoDb (RDS)
- AWS: RDS, S3, Route453, and CloudFront for RTMP Streaming
- PHP 5.5+
- Phalcon Framework 2.x
- BS3/SASS/CSS/jQuery
- Git 2.x
- Python 2.7 with PIP: Fabric (For Deployment)
- NodeJS: bower, gulp

### Installation
First update

    sudo apt-get update

Then install

    sudo apt-get install\
    apache2\
    libapache2-mod-php5\
    redis-server\
    php5\
    php5-dev\
    memcached\
    redis-server\
    php5-redis\
    python\
    python-dev\
    python-pip\
    openssl\
    mysql-server\
    git\
    vim\
    htop

1: (Optional) Install the Phalcon Vagrant Playground I wrote for Open Source. Instructions are on the readme here: [https://github.com/phalcon/vagrant](https://github.com/phalcon/vagrant)

2: Install composer locally or SSH into the vagrant box. [https://getcomposer.org/download/](https://getcomposer.org/download/).

    $ composer install

3: Dump `schema.sql` into a new database titled `jream`.

4: Make sure `application/cache` is writable via the server (below)

5: Install Node + NPMs:

    $ sudo npm install -g bower gulp

    # Install Gulp Dependencies (In the development/package.json folder):
    $ npm install

    # Usage:
    $ gulp watch    # Compile on-change
    $ gulp          # Compile once

### Other Development Components

These are components used to deploy, and manage various things. Note I use **Ubuntu 14 x64** for all servers.

#### Install and run PHPUnit

    $ sudo apt-get install phpunit
    $ cd tests
    $ phpunit

#### Install Pip & Fabric

    $ sudo apt-get install python-pip
    $ pip install fabric

#### Using Codeception
From the root folder (Where you can see `/vendor/`) run:

    $ vendor/bin/codecept run

### Server Cache

Make sure the application/cache folder is writable.

    $ chown -R www-data:www-data /var/www/jream.com
    $ usermod -a -G www-data jesse
    $ chmod 770 -R /var/www/jream.com

### Test Credentials

    Paypal Test:
    aniyishay-facilitator@gmail.com

## Apache Configuration

To create an .htpasswd for the development environment run `sudo htpasswd -c /etc/apache2/.htpasswd <username>`

    ServerName jream.com
    <VirtualHost *:80>
            ServerAdmin server@jream.com
            ServerName  jream.com
            ServerAlias jream.com www.jream.com

            # Indexes + Directory Root.
            DirectoryIndex index.php
            DocumentRoot /var/www/jream.com/htdocs/public/

            # Logfiles
            ErrorLog  /var/www/logs/error.jream.com.log
            CustomLog /var/www/logs/access.jream.com.log combined
    </VirtualHost>

    <VirtualHost *:80>
        ServerAdmin server@jream.com
        ServerName dev.jream.com
        ServerAlias dev.jream.com

        # Indexes + Directory Root.
        DirectoryIndex index.php
        DocumentRoot /var/www/dev.jream.com/htdocs/public

        # Protect
        <Directory /var/www/dev.jream.com/htdocs/public>
            AuthType Basic
            AuthName "Restricted Content"
            AuthUserFile /etc/apache2/.htpasswd
            Require valid-user
        </Directory>

        # Logfiles
        ErrorLog /var/www/logs/error.dev.jream.com.log
        CustomLog /var/www/logs/access.dev.jream.com.log combined
    </VirtualHost>

    <VirtualHost *:443>
            ServerAdmin server@jream.com
            ServerName  jream.com
            ServerAlias jream.com

            # Indexes + Directory Root.
            DirectoryIndex index.php
            DocumentRoot /var/www/jream.com/htdocs/public/

            # Logfiles
            ErrorLog  /var/www/logs/error.ssl.jream.com.log
            CustomLog /var/www/logs/access.ssl.jream.com.log combined

            # SSL
            SSLEngine on
            SSLCertificateFile /etc/apache2/ssl/jream.com/jream_com.crt
            SSLCertificateKeyFile /etc/apache2/ssl/jream.com/server.key
            SSLCACertificateFile /etc/apache2/ssl/jream.com/jream_com.cer
    </VirtualHost>

# Testing
Use codeception, which can be symlinked from composer via:

    ln -s vendor/bin/codecept .

If nothing exists, bootstrpa it with:

    ./codecept build

Create a Test with:

    ./codecept generate:cept acceptance Welcome

Then you'd write a file here: `tests/acceptance/WelcomeCept.php`

    <?php
    $I = new AcceptanceTester($scenario);
    $I->wantTo('ensure that frontpage works');
    $I->amOnPage('/');
    $I->see('Home');
    ?>

With an active PHP directory, add the host here:

    tests/acceptance.suite.yml

Then run the test with

    ./codecept run


# Crashplan

If the servers go haywire these things must be done:

- Make sure MySQL has access to the HOST IP
- Check the DNS in AWS, Check for Health Checking
- Make sure composer is updated
- Check /var/logs/apache2/error.log