# Generic HTTP Web Service Server in PHP (GHoWSt)

![Logo](http://www.weburg.com/ghowst/images/ghowstlogo.png)

## An example server providing the Web service and an HTML-only static client

> [!NOTE]
> This is a work in progress to bring a GHoWSt PHP server library up to parity with the Java version. Refer to the Java version until this note is removed.

> [!CAUTION]
> This server and its code isn't meant to run in a production or otherwise
> public environment, as it lacks enough error checking and restrictions to be
> safe. It's meant to run locally for prototyping and example purposes only.

Simple MVC architecture where controllers are in public/, views are in php/views/, and the model is whatever is needed, kept in php/.

Routing is done through a combination of rewrites and controller logic. Path parameters are not used. Query string and URI parsing are done as needed.

### General setup

For development, we're going to use the Apache handler with the PHP module. This allows use of the .htaccess files included in this project. If using another method to improve security, performance, and scalability in a production environment, the rewrites used in .htaccess will need to be moved into the server configuration.

### Linux server setup (Debian/Ubuntu example)

#### Install Apache and PHP

Run:
`sudo apt install apache2`
`sudo apt install php libapache2-mod-php php-cli php-xdebug php-curl php-xml php-mbstring`
(some extensions are required by Composer and PHPUnit, refer to install instructions for more information)

To test, create phpinfo.php file with contents `<?php phpinfo() ?>` in `/var/www/html` and go to http://localhost/phpinfo.php. Then delete the file.

#### Configure

For development and portability, enable .htaccess. Alternately, add .htaccess config to the Apache config file.
Edit /etc/apache2/apache2.conf so that <Directory /var/www> sets `AllowOverride All`

Enable mod_rewrite to route all requests:
`sudo a2enmod rewrite`

Prepare for deployment:
chown and chmod /var/www so local user can write to it.

Add `/var/www/php` to php.ini's include_path. Do for both CLI and Apache:
- `/etc/php/8.1/cli/php.ini`
- `/etc/php/8.1/apache2/php.ini`

Add `xdebug.mode=debug` at the end of `/etc/php/8.1/apache2/php.ini` to enable remote debugging.

Restart Apache:
`sudo systemctl reload apache2`

### Windows server setup

#### Install Apache and PHP

Download Apache2, PHP (thread safe), and Xdebug (match version to PHP). Extract to C:\Apache24, C:\php, and C:\php\ext\php_xdebug.dll respectively.

In C:\php\php.ini:
- Enable extensions curl, openssl, mbstring
  (some extensions are required by Composer and PHPUnit, refer to install instructions for more information)
- Add to the end of the file:
  ```
  [xdebug]
  zend_extension=xdebug
  xdebug.mode=debug
  xdebug.client_host=127.0.0.1
  xdebug.client_port=9003

In C:\Apache24\conf\httpd.conf:
- Add to the end of the file:
  ```
  LoadModule php_module "c:/php/php8apache2_4.dll"
  <FilesMatch \.php$>
      SetHandler application/x-httpd-php
  </FilesMatch>
  # configure the path to php.ini
  PHPIniDir "C:/php"

Start Apache. You can choose to run in on the command line, as an external tool in your IDE, or as service. Consult the documentation for how to start Apache.

To test, create phpinfo.php file with contents `<?php phpinfo() ?>` in `C:\Apache24\htdocs` and go to http://localhost/phpinfo.php. Then delete the file.

#### Configure

For development and portability, enable .htaccess. Alternately, add .htaccess config to the Apache config file.
Edit C:\Apache24\conf\httpd.conf so that `<Directory "${SRVROOT}/htdocs">` sets `AllowOverride All`

In addition, enable mod_rewrite to route all requests by uncommenting the below line:
`LoadModule rewrite_module modules/mod_rewrite.so`

Add `c:\Apache24\php` to php.ini's include_path.

Restart Apache.

### IDE (PhpStorm) setup

Add [project_root]/php to IDE's Include Path list (right click php folder, then select Mark Directory As -> Sources Root)

Configure Deployment to use the Folder `/var/www` (Linux) or `C:\Apache24` (Windows) and then add Mappings:
- Copy public project folder to localhost to `/var/www/html` (Linux) or `C:\Apache24\htdocs` (Windows) and set Web path to `/`
- Copy php project folder to localhost to `/var/www/php` (Linux) or `C:\Apache24\php` (Windows)

Deploy both directories initially, creating any initial directories by hand if needed. Set upload to Automatic for rapid and seamless development. Remove any existing index.html if desired.

Configure Servers to have a localhost server. To allow debugging, select to Use path mappings, then add the same two paths set up for deployment.

Install Composer (preferably globally). Install packages.

Now test the deployed application by going to http://localhost and make sure everything works.

### Running the tests

To run unit tests only:

`composer run-script test`

To run unit and integration tests:

`composer run-script verify`
