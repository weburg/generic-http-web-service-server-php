# Generic HTTP Web Service Server in PHP (GHoWSt)

## An example server providing the Web service and an HTML-only static client

Simple MVC architecture where controllers are in public/, views are in php/views/, and the model is whatever is needed, kept in php/.

Routing is done through a combination of rewrites and controller logic. Path parameters are not used. Query string and URI parsing are done as needed.

### Linux / Ubuntu server setup

#### Install Apache and PHP

Run:
`sudo apt install apache2`
`sudo apt install php libapache2-mod-php php-cli php-xdebug php-curl php-xml php-mbstring`
(the latter two are required by PHPUnit)

To test, create phpinfo.php file with contents `<?php phpinfo() ?>` in `/var/www/html` and go to http://localhost/phpinfo.php. Then delete the file.

#### Configure

For development and portability, enable .htaccess. Alternately, add .htaccess config to the Apache config file.
Edit /etc/apache2/apache2.conf so that <Directory /var/www> sets `AllowOverride All`

Enable mod_rewrite to route all requests:
`sudo a2enmod rewrite`

Restart Apache:
`sudo systemctl reload apache2`

Prepare for deployment:
chown and chmod /var/www so local user can write to it.

Add `/var/www/php` to php.ini's include_path. Do for both CLI and Apache:
- `/etc/php/8.1/cli/php.ini`
- `/etc/php/8.1/apache2/php.ini`

### IDE (PhpStorm) setup

Add [project_root]/php to IDE's Include Path list.

Configure Servers to have localhost

Configure Deployment to:
- copy public project folder to localhost in `/var/www/html`
- copy php project folder to localhost in `/var/www/php`

Set upload to Automatic for rapid and seamless development.

Install Composer (preferably globally). Install packages.

### Running the tests

To run unit tests only:

`phpunit`

To run unit and integration tests:

`phpunit --testsuite unit,integration`