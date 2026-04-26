# Generic HTTP Web Service Server in PHP (GHoWSt)

![Logo](http://www.weburg.com/ghowst/images/ghowstlogo.png)

## An example server providing the Web service and an HTML-only static client

> [!NOTE]
> This is a work in progress to bring a GHoWSt PHP server library up to parity
> with the Java version. Refer to the Java version until this note is removed.

Simple MVC architecture where controllers are in public/, views in php/views/,
and the model is whatever is needed, kept in php/. Routing is done through a
combination of Web server rewrites and controller logic.

### General setup

We're going to use the Apache handler with the PHP module. This allows use of
the .htaccess files included in this project. For security, performance, and
scalability in a production environment, a reverse proxy like Nginx should be
added in front. If that's not enough, then use of other approaches can be
considered, as well as moving the rewrites used in .htaccess files into the
server configuration.

#### Create the virtual host file ####

For Windows, in Apache's conf/extra/httpd-vhosts.conf, comment out the default virtual
hosts entries and add the below to the bottom of the file, substituting for
your project root absolute path.

For Linux (Ubuntu), add the below to /etc/apache2/sites-available/ghowst-duets.conf

```
Define GHOWSTROOT "<project root>"

<Directory "${GHOWSTROOT}/public">
	Options Indexes FollowSymLinks
	AllowOverride All
	Require all granted
</Directory>

Listen 8081

<VirtualHost *:8081>
    DocumentRoot "${GHOWSTROOT}/public"
</VirtualHost>
```

### Linux setup (Ubuntu)

#### Install Apache and PHP

Run:
`sudo apt install apache2`
`sudo apt install php libapache2-mod-php php-cli php-xdebug php-curl php-xml php-mbstring`
(some extensions are required by Composer and PHPUnit, refer to install
instructions for more information)

#### Configure

Enable mod_rewrite to route all requests:
`sudo a2enmod rewrite`

Add `<project root>/php` to php.ini's include_path:
- `/etc/php/8.1/apache2/php.ini`

Running tests already uses its own bootstrap file to add it to the include path,
but you can add it to the CLI as well if you want:
- `/etc/php/8.1/cli/php.ini`

Add `xdebug.mode=debug` at the end of `/etc/php/8.1/apache2/php.ini` to enable
remote debugging.

Enable the virtual host file:
`sudo a2ensite ghowst-duets.conf`

Make sure permissions on the <project root> folder and its subfolders are set to
755 and that files are set to 644 if Apache can't serve it. In addition, make
sure that every directory preceding the <project path> has the execute bit set
for at least the www-data user. For example, if running the site from your user
home directory, you can change your home directory group to www-data and give
group execute permission, e.g. 750. Otherwise, Apache won't be able to traverse
into any subdirectories.

### Windows setup

#### Install Apache and PHP

Download Apache2, PHP (thread safe), and Xdebug (match version to PHP). Extract
to C:\Apache24, C:\php, and C:\php\ext\php_xdebug.dll respectively.

In C:\php\php.ini:
- Enable extensions curl, openssl, mbstring (some extensions are required by
  Composer and PHPUnit, refer to install instructions for more information)
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

#### Configure

Enable mod_rewrite to route all requests by uncommenting the below line in
the Apache httpd.conf:
`LoadModule rewrite_module modules/mod_rewrite.so`

Add `<project root>\php` to php.ini's include_path.

In the Apache httpd.conf:
- Uncomment the Virtual hosts Include:
  ```
  # Virtual hosts
  Include conf/extra/httpd-vhosts.conf
  
### IDE setup (PhpStorm) and final steps

Add <project root>/php to IDE's Include Path list (Settings -> PHP -> Include Path)

Mark directories as you wish, e.g. Sources Root, Templates, Test Sources, etc. (right click desired folder, then select Mark Directory As)

Configure Servers to have a localhost server and set the port to 8081. Set the
debugger to Xdebug.

Install Composer (preferably globally). Install packages.

Start (or reload) Apache. In Windows, you can choose to run in on the command
line, as an external tool in your IDE, or as service. Consult the documentation
for operating Apache. In Linux (Ubuntu), run 'sudo systemctl reload apache2'

Now test the deployed application by going to http://localhost:8081 and make
sure everything works.

In Linux (Ubuntu), as well as Windows, if port 8081 isn't working, it might be
blocked by a firewall. Check the documentation or online for help. Verify that
at least http://localhost is working and check the Apache error log for any
errors.

### Running the tests

To run unit tests only:

`composer run-script test`

To run unit and integration tests:

First, ensure the Apache server is running, then:

`composer run-script verify`