<?php
/**
 * Equipment Server
 *
 * @author Lab Analytix <http://labanalytix.com>
 * @version 0.1
 */

if (!isset($this->equipment_host) || !isset($this->equipment_port))
{
	$this->send($client, 'Invalid equipment configuration. Could not connect...');
	exit;
}

echo "Remote Scale Connection Test over TCP/IP\n\r";

//  Set the IP address and port number
$scale_address 	= $this->equipment_host;
$scale_port 	= $this->equipment_port;

// Create a TCP/IP socket and connection to equipment
$scale_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed");
echo "Attempting to connect to $scale_address on port $scale_port …";
$result = socket_connect($scale_socket, $scale_address, $scale_port);
if ($result === false) {
	echo 'socket_connect() failed.';
	echo "\n\rReason: ($result)" . socket_strerror(socket_last_error($scale_socket)) . "\n\r";
	$this->send($client, 'There is already a connection to this scale. Please try again later.');
	exit;
}
else {
	echo "OK.\n\r";
}

//  Send the scale commands
$in = 'S\r\n';
$out = '';
echo 'Sending Request…';
socket_write($scale_socket, $in, strlen($in));
echo "OK.\n\r";


//  Read from the scale any responses until the operator ends
$data = '';
while(TRUE) {
	$buffer = socket_read_normal($scale_socket);
	$this->send($client, $buffer);
	$data .= $buffer;
	
	// if the connection is lost, then this loop will spin out of control, so need to check the connection is still there periodically; 
	if (strpos($data, '----------------') > 0 or $buffer == NULL){ break; }
}