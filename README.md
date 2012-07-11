# This is my README
## Dependencies:
php version > 5.1
php-cli
php-posix
php-pcntl
/etc/init.d/functions should exist

cd /usr/local/
tar -zcvf laberp-equipment-server.tar.gz 
cd laberp-equipment-server

## To be ran on a redhat based server
cp support-files/scaleserver to /etc/init.d/
chmod +x scaleserver

to run:
service scaleserver start
service scaleserver stop
service scaleserver restart
service scaleserver status

## Front end
front.html is an example file on how to create a web socket connection

## equipment drivers
We will have a driver for each piece of equipment we want to talk to. 

Define all of the servers you wish to connect to on libs/config.php

Each server is a scale

## Resources
* https://github.com/srchea/PHP-Push-WebSocket
* http://us.php.net/manual/en/function.pcntl-fork.php
* http://us.php.net/manual/en/function.pcntl-fork.php#106860 (fork in foreach)
* http://www.godlikemouse.com/2011/03/31/php-daemons-tutorial/
