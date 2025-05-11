# generic-http-web-service-server-php

## An example server providing the Web service and an HTML-only static client

Early initial setup

### Linux / Ubuntu server setup

sudo apt install apache2
sudo apt install php libapache2-mod-php

chown and chmod /var/www/html so local user can write to it.

Create test.php file with contents <?php phpinfo(); ?> in /var/www/html and go to http://localhost/test.php

Edit /etc/apache2/apache2.conf so that <Directory /var/www> sets AllowOverride All
sudo systemctl reload apache2

### IDE (PhpStorm) setup

Configure Servers to have localhost

Configure Deployment to copy local project root to localhost in /var/www/html under generichttpws