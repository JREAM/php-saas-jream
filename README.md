# JREAM 7.0 
This is the website of JREAM LLC for streaming media and training courses SASS.
(C) 2017 JREAM LLC.

JREAM (Jesus Rules Everything Around Me) is a website for streaming videos I've made over the years.
The system is built to serve people content on the fly after they purchase a product. Reliablity
is important since JREAM LLC provides a serve. To solve this, AWS is utilized for fall-back servers, Redundant Storage, Geolocalized CDN, and Multi A-Z DB.

## Using Mailcatcher

For localhost testing use mailcatcher with Ruby:

```
gem install mailcatcher
mailcatcher
```

PHP settings
```
Mailpath: /usr/bin/sendmail
Protocol: smtp
Host: localhost
Port: 1025
```

- Go to http://localhost:1080/
- Send mail through smtp://localhost:1025

##TODO

- [ ] (Skip) Middleware CSRF Token.
- [ ] HLS with HTML5 through RMTP.
- [ ] Check Promotion for Expiration, ONLY IF THEY APPLY IT
- [ ] Make URL so promotion can trigger in a cookie and stay alive.
- [ ] Test out the pricing with the percentages!
- [ ] Test out the pricing with the price~
- eg:
  - [ ] Promotions Table has no product_id and no user_id, it applies to ALL users globally.
  - [ ] Promotions Table has two records with product_id, it applies ONLY to those products
  - [ ] Promotion ALWAYS checks DELETED_AT, and EXPIRES_AT before doing anything.
- [ ] This will apply to the STANDARD promo code page (regular check)
- [ ] This will apply to the new checkout page.


#### Using Codeception
From the root folder (Where you can see `/vendor/`) run:

```sh
vendor/bin/codecept run
```

### Server Cache

Make sure the application/cache folder is writable.

```sh
chown -R www-data:www-data /var/www/jream.com
usermod -a -G www-data jesse
chmod 770 -R /var/www/jream.com
```

### Test Credentials

```
Paypal Test: aniyishay-facilitator@gmail.com
```

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


##Fix for Webpack SASS

So the easiest fix for now is to downgrade to npm@5.1.0 using
```
npm i -g npm@5.1.0
```
Then cleanup and reinstall dependencies:
```
npm cache clean -f
rm -rf node_modules/ package-lock.json
npm i
```
