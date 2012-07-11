<?php

/**
 * Equipment Server
 *
 * @author Lab Analytix <http://labanalytix.com>
 * @version 0.1
 */
$servers = parse_ini_file("/etc/laberp-equipment-server.ini", true);

/* Common Functions ******************************************************** */

//  found this code in the php manual for socket_read; 
// it seems to handle the block reading quite well; does the fread do the same?
function socket_read_normal($socket, $end=array("\r", "\n"))
{
	if (is_array($end))
	{
		foreach ($end as $k => $v)
		{
			$end[$k] = $v{0};
		}
		$string = '';
		while (TRUE)
		{
			if (($char = socket_read($socket, 1)) === false)
			{
				return false;
			}
			$string.=$char;
			foreach ($end as $k => $v)
			{
				if ($char == $v)
				{
					return $string;
				}
			}
		}
	}
	else
	{
		$endr = str_split($end);
		$try = count($endr);
		$string = '';
		while (TRUE)
		{
			$ver = 0;
			foreach ($endr as $k => $v)
			{
				if (($char = socket_read($socket, 1)) === false)
				{
					return false;
				}
				$string.=$char;
				if ($char == $v)
				{
					$ver++;
				}
				else
				{
					break;
				}
				if ($ver == $try)
				{
					return $string;
				}
			}
		}
	}
}

?>