# This is my README
## Dependencies:
php version > 5.1
php-cli
php-posix
php-pcntl
/etc/init.d/functions should exist

## Installation
* cd /usr/local/
* wget <url to laberp-equipment-server.tar.gz>
* tar -zcvf laberp-equipment-server-$hash.tar.gz
	* `ln -s laberp-equipment-server-$hash laberp-equipment-server`
* cd laberp-equipment-server
* `cp support-files/laberp-equipment-server.ini /etc/.`
	* Edit the ini file to setup all of the servers you wish to run
* Add /usr/local/laberp-equipment-server/bin to $PATH
	* Add `PATH="$PATH:/usr/local/laberp-equipment-server/bin` to your .bash_profile or environment config"
* `cp support-files/scaleserver to /etc/init.d/`
	* You might have to chmod +x scaleserver to execute it. 
* Now we can start the server!
	* `/sbin/service scaleserver start`
	* `/sbin/service scaleserver stop`
	* `/sbin/service scaleserver restart`
	* `/sbin/service scaleserver status`

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
