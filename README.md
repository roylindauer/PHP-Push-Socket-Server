# This is my README
## Dependencies:
php version > 5.1
php-cli
php-posix
php-pcntl
/etc/init.d/functions should exist

## To be ran on a redhat based server
put scaleserver in /etc/init.d/
chmod a+x scaleserver

to run:
scaleserver start
scaleserver stop
scaleserver restart
scaleserver status

## socket-server and supporting libraries
put socket-server and libs under /var/www/vhosts/laberp/html/services
chmod a+x socket-server

## Front end
front.html is an example file on how to create a web socket connection

## equipment drivers
We will have a driver for each piece of equipment we want to talk to. 

##############################################################################

Define all of the servers you wish to connect to on libs/config.php
Each server is a scale


#### Resources
https://github.com/srchea/PHP-Push-WebSocket
http://us.php.net/manual/en/function.pcntl-fork.php
http://us.php.net/manual/en/function.pcntl-fork.php#106860 (fork in foreach)
http://www.godlikemouse.com/2011/03/31/php-daemons-tutorial/