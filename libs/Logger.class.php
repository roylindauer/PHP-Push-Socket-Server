<?php
/**
 * Equipment Server
 *
 * @author Lab Analytix <http://labanalytix.com>
 * @version 0.1
 */

class Logger
{
	var $log_path;
	var $threshold	= 1;
	var $dateformat	= 'Y-m-d H:i:s';
	var $_enabled	= TRUE;
	var $_levels	= array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');
	
	function __construct( $log_path = '', $threshold = '', $dateformat = '' ){
		$this->log_path = ($log_path != '') ? $log_path : '/var/log/socket_server.log';
		
		if (is_numeric($threshold))
		{
			$this->_threshold = $threshold;
		}
			
		if ($dateformat != '')
		{
			$this->dateformat = $dateformat;
		}
	}

	function write_log($level = 'error', $msg)
	{		
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	
		$level = strtoupper($level);
		
		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}
	
		$message  = '';
			
		if ( ! $fp = @fopen($this->log_path, "a"))
		{
			return FALSE;
		}

		$message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->dateformat). ' --> '.$msg."\n";
		
		flock($fp, LOCK_EX);	
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);
	 		
		return TRUE;
	}
}
?>