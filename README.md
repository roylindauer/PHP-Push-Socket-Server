# This is my README
## Dependencies:
* php version > 5.1
* php-cli
* php-posix
* php-pcntl
* /etc/init.d/functions should exist
* It only supports RedHat based servers.

## Installation
* `cd /usr/local/`
* `wget <url to laberp-equipment-server.tar.gz>`
* `tar -zcvf laberp-equipment-server-$hash.tar.gz`
	* `ln -s laberp-equipment-server-$hash laberp-equipment-server`
* `cd laberp-equipment-server`
* `cp support-files/laberp-equipment-server.ini /etc/.`
	* Edit the ini file to setup all of the servers you wish to run
* Add /usr/local/laberp-equipment-server/bin to $PATH
	* Add `PATH="$PATH:/usr/local/laberp-equipment-server/bin` to your .bash_profile or environment config
* `cp support-files/laberp_equipment_server to /etc/init.d/` 
* Now we can start the server!
	* `/sbin/service laberp_equipment_server start`
	* `/sbin/service laberp_equipment_server stop`
	* `/sbin/service laberp_equipment_server restart`
	* `/sbin/service laberp_equipment_server status`

## How does this work?
* laberp_equipment_server is a script that launches the php socket server "laberp-equipment-server"
* laberp-equipment-server then forks a process for each scale defined in the ini configuration
	* It creates n forks of itself (1 child process for each scale defined in the configuration)
* Each child process listens for data from a scale
* When it receives data it sends that information back to the client that is connected to it. 

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
