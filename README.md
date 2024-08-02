# JREAM 7.0
This is the website I built for JREAM LLC several years ago.
This remains for a portfolio piece of code. This document is not conclusive. The code was written by me and for me. :-)

- **Overview**
- Written in [Phalcon PHP](https://phalcon.io) framework.
- VPS Hosted on Ubuntu LTS Server ([Linode](https://www.linode.com)).
  - PHP 7
  - Apache 2
  - WebPack for Assets
    - SaaS
    - Misc Images
    - Webpack Mix
    - _Packages are out of date of course :)_
  - Ruby Mailcatcher for Testing Emails
  - PHP Codeception for Tests
  - Python Fabric for Basic Deployment Tasks
  - Bash `cli.sh` for Phalcon Based `app/Tasks`
- Developed as a paid SaaS/LMS for courses I recorded.
- Blog Submodule, however `.gitmodules` of `jream/blog` is no longer available.
- Features Hosted through AWS:
  - Multi A-Z MySQL Database.
    - AWS MySQL RDB (Upfront).
  - Streaming Video and Images:
    - AWS S3 Storage.
    - AWS CloudFront for RMTP Streaming.
  - 3rd Party Streaming Video Player
- Paid Members for Full Courses (Stripe API).
- API Keys are Rolled/Not Active:
  - Paypal REST API.
  - Stripe REST API.

## Preview Images

![jream-00](https://private-user-images.githubusercontent.com/145959/346989307-f5b63321-8651-4f36-a95b-18c9d6edc7ef.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MjA1MzQ0OTMsIm5iZiI6MTcyMDUzNDE5MywicGF0aCI6Ii8xNDU5NTkvMzQ2OTg5MzA3LWY1YjYzMzIxLTg2NTEtNGYzNi1hOTViLTE4YzlkNmVkYzdlZi5wbmc_WC1BbXotQWxnb3JpdGhtPUFXUzQtSE1BQy1TSEEyNTYmWC1BbXotQ3JlZGVudGlhbD1BS0lBVkNPRFlMU0E1M1BRSzRaQSUyRjIwMjQwNzA5JTJGdXMtZWFzdC0xJTJGczMlMkZhd3M0X3JlcXVlc3QmWC1BbXotRGF0ZT0yMDI0MDcwOVQxNDA5NTNaJlgtQW16LUV4cGlyZXM9MzAwJlgtQW16LVNpZ25hdHVyZT1mMDQ0ZThkZTRlMGQ2YWQ1ZGQ0ZDY0OWM2YzFjNDQyYTU3MjVkY2I0NGVjZWUwM2UyMDI5OTIyMzFmOTZkYzg1JlgtQW16LVNpZ25lZEhlYWRlcnM9aG9zdCZhY3Rvcl9pZD0wJmtleV9pZD0wJnJlcG9faWQ9MCJ9.tim-Nt1SfSpZYgSS23TAb3-pZXn2aa8mYL04ozqu6D8)

![jream-01](https://private-user-images.githubusercontent.com/145959/346989311-8a9caed0-0062-4ba8-b55c-2c195531fbb4.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MjA1MzQ0OTMsIm5iZiI6MTcyMDUzNDE5MywicGF0aCI6Ii8xNDU5NTkvMzQ2OTg5MzExLThhOWNhZWQwLTAwNjItNGJhOC1iNTVjLTJjMTk1NTMxZmJiNC5wbmc_WC1BbXotQWxnb3JpdGhtPUFXUzQtSE1BQy1TSEEyNTYmWC1BbXotQ3JlZGVudGlhbD1BS0lBVkNPRFlMU0E1M1BRSzRaQSUyRjIwMjQwNzA5JTJGdXMtZWFzdC0xJTJGczMlMkZhd3M0X3JlcXVlc3QmWC1BbXotRGF0ZT0yMDI0MDcwOVQxNDA5NTNaJlgtQW16LUV4cGlyZXM9MzAwJlgtQW16LVNpZ25hdHVyZT1lNDc5OGFjNzRlMjk4OWFhMWM2ODZiM2IwNzZkYTUzNzg4ODhkZGIxMjdiYjY5OTg0MzhlY2M3ZjhlOWMxM2MxJlgtQW16LVNpZ25lZEhlYWRlcnM9aG9zdCZhY3Rvcl9pZD0wJmtleV9pZD0wJnJlcG9faWQ9MCJ9.8UgX1bnIS0XaMnZujrMLOcE0wfclQYd3x2ujSpP2Kns)

## Dev Subdomain

At the time, this was setup using Apache. I'd likely use Nginx now but here's the configuration.

- Make a `.htpasswd` to protect the `dev` URL.
```bash
cd /etc/apache2/htpasswd
htpasswd -cB dev_jream.htpasswd jesse
```

- Setup the Apache Config:

```apacheconfig
<VirtualHost *:80>
    ServerName dev.jream.com
    ServerAdmin hello@jream.com

    # Notice the /dev/ subfolder
    DocumentRoot /var/www/dev/jream.com/public
    DirectoryIndex index.php

    <Directory /var/www/dev/jream.com/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    #Require all granted

    # Path to the generated .htpasswd
    AuthUserFile /etc/apache2/htpasswd/dev_jream.htpasswd
    AuthType Basic
    AuthName "Develop Area"
    Require valid-user

    Order Allow,Deny
    Deny from All

    Satisfy Any
    </Directory>

</VirtualHost>
```

## Setup Mailcatcher

- For localhost email testing use mailcatcher with Ruby

```bash
gem install mailcatcher
mailcatcher
```

- Configure PHP settings for Mailcatcher

```yaml
Mailpath: /usr/bin/sendmail
Protocol: smtp
Host: localhost
Port: 1025
```

- Go to http://localhost:1080/
- Send mail through smtp://localhost:1025

### Using Codeception

- From the root folder (Where you can see `/vendor/`) run:

```bash
vendor/bin/codecept run
```

### Server Cache

- Make sure the application/cache folder is writable.
- If setting up a `/dev` path it would also be required there.

```bash
chown -R www-data:www-data /var/www/jream.com
usermod -a -G www-data jesse
chmod 770 -R /var/www/jream.com
```

### PayPal Test Credentials

- Get the PayPal credentials from the API.

```yaml
Paypal Test: username-facilitator@gmail.com
```

## Apache Configuration

To create an .htpasswd for the development environment run `sudo htpasswd -c /etc/apache2/.htpasswd <username>`

```apache
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
```

# Testing

- Use codeception, which can be symlinked from composer via:

```bash
ln -s vendor/bin/codecept .
```

- If nothing exists, bootstrap it with:

```bash
./codecept build
```

- Create a Test with:

```bash
./codecept generate:cept acceptance Welcome
```

- Then you'd write a file here: `tests/acceptance/WelcomeCept.php`

```php
<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/');
$I->see('Home');
```

- With an active PHP directory, add the host here:

```
tests/acceptance.suite.yml
```

Then run the test with

```bash
./codecept run
```

# Crashplan

If the servers go haywire these things must be done:

- Make sure MySQL has access to the `HOST IP` (_Using AWS RDB_)
- Check the `DNS` in `AWS RDB`, Look at `Health Check`
- Make sure `Composer` is Updated
- Check `/var/logs/apache2/error.log`


## Fix for Webpack SASS

(_legacy_) So the easiest fix for now is to downgrade to npm@5.1.0 using

```bash
npm i -g npm@5.1.0
```
Then cleanup and reinstall dependencies:
```
npm cache clean -f
rm -rf node_modules/ package-lock.json
npm i
```


## TODO

This list is expired, some features will no longer be added.

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


---

MIT Open Source

&copy; 2018 JREAM.com | Jesse Boyer
