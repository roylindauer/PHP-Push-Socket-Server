<?php

/**
 * All scales should implement the scale interface to ensure that it meets our apis standards
 */
interface ScaleInterface
{

	public function setErrorCodes();
}

/**
 * Equipment Type "Scale" Base Class
 */
class Scale
{

	public $server = '127.0.0.1';
	public $port = 23;
	public $connection_timeout = 90;
	public $socket = null;
	public $connection = null;
	public $line_length = 1024; // the default length of a line
	public $signature_end = ''; // string to look for to determine we are at the end of a recorded block
	public $model_line = 1; // the line number tha the model name is on in our received data
	public $serial_num_line = 2; // the line number tha the serial number is on in our received data
	public $error_codes = array();
	public $model_name = '';
	public $serial_number = '';
	public $command = false;

	/**
	 *
	 */
	public function __construct($params=array())
	{
		if (is_array($params))
		{
			foreach ($params as $k => $v)
			{
				$this->$k = $v;
			}
		}
	}

	/**
	 *
	 */
	public function connect()
	{
		try
		{
			if (!$this->server || !$this->port)
			{
				throw new Exception('Invalid configuration. You must have a server and/or port specified');
				return false;
			}

			// create a socket
			if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false)
			{
				throw new Exception('Could not create connection: ' . socket_strerror(socket_last_error()));
				return false;
			}

			// connect to socket
			if (($this->connection = socket_connect($this->socket, $this->server, $this->port)) === false)
			{
				throw new Exception('Could not create connection: ' . socket_strerror(socket_last_error()));
				return false;
			}

			return true;
		}
		catch (Exception $e)
		{
			$this->error($e);
			return false;
		}
	}

	/**
	 *
	 */
	public function disconnect()
	{
		try
		{
			if (!$this->socket)
			{
				throw new Exception('There is no connection to close');
				return true;
			}

			socket_close($this->socket);
			return true;
		}
		catch (Exception $e)
		{
			$this->error($e);
			return false;
		}
	}

	/**
	 *
	 */
	public function read(Server $server, Client $client)
	{
		try
		{
			if (!$this->socket)
			{
				throw new Exception('There is not a valid socket connection');
				return false;
			}

			$data = '';
			#$line_num = 0;

			echo 'Sending Request...';
			socket_write($this->socket, $this->command, strlen($this->command));
			echo "OK.\n\r";

			while (true)
			{
				// the second line should be the model number
				// the third line should be the serial number
				// get these two pieces of data and compare with this objects properties
				// if they match, continue, otherwise error out
				$buffer = $this->socket_read_normal($this->socket);

				if (preg_match($this->error_code_pattern, $buffer))
				{
					$buffer = $this->parseErrorCode($buffer);
				}

				/*
				  if ($line_num == $this->model_line)
				  {
				  $recv_model_data['model'] = $buffer;
				  }
				  if ($line_num == $this->serial_num_line)
				  {
				  $recv_model_data['serial_number'] = $buffer;
				  // now check this machine out
				  if (!preg_match('#('.$this->model_name.')#', $recv_model_data['model']))
				  {
				  throw new Exception('Model name ('.$recv_model_data['model'].') does not match! Please double check your settings');
				  break;
				  }
				  if (!preg_match('#('.$this->serial_number.')#', $recv_model_data['serial_number']))
				  {
				  throw new Exception('Serial number ('.$recv_model_data['serial_number'].') does not match! Please double check your settings');
				  break;
				  }
				  }
				 */

				$data .= $buffer;

				// the end block of a recorded data set form the machine has a series of dashes
				// if we run across this, we should break out of the loop as the user
				// has ended their weighing tasks
				if (preg_match('#(' . $this->signature_end . ')#', $buffer))
				{
					break;
				}

				// send data to client
				$server->send($client, $buffer);

				#$line_num++;
			}
			return $data;
		}
		catch (Exception $e)
		{
			$this->error($e);
		}
	}

	/**
	 *
	 */
	public function parseErrorCode($code)
	{
		try
		{
			if (!array_key_exists($code, $this->error_codes))
			{
				return 'Could not find error code ' + $code;
			}
			else
			{
				return $this->error_codes[$code];
			}
		}
		catch (Exception $e)
		{
			$this->error($e);
		}
	}

	/**
	 *
	 */
	public function error($e)
	{
		echo 'Caught exception: ' . $e->getMessage() . '<br />';
	}

	/**
	 *
	 */
	private function socket_read_normal($socket, $end=array("\r", "\n"))
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
				$char = socket_read($socket, 1);
				$string.=$char;
				foreach ($end as $k => $v)
				{
					if ($char == $v)
					{
						return trim($string);
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
					$char = socket_read($socket, 1);
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
						return trim($string);
					}
				}
			}
		}
	}

}

/**
 * Specific scale class
 * This class should describe the specific scales error codes and model info
 */
class FX300i extends Scale implements ScaleInterface
{

	/**
	 *
	 */
	public function __construct($params)
	{
		parent::__construct($params);

		$this->model_name = 'FX-300i';
		$this->serial_number = '15603968';

		$this->signature_end = '---'; // string to look for to determine we are at the end of a recorded block
		$this->model_line = 1;
		$this->serial_num_line = 2;

		$this->command = "S\r\n";

		$this->error_code_pattern = '#(EC,E[0-9]{0,})#';
	}

	/**
	 *
	 */
	public function setErrorCodes()
	{
		$this->error_codes = array(
			'EC,E01' => 'Undefined command error',
			'EC,E02' => 'Not Ready',
			'EC,E03' => 'Timeout Error',
			'EC,E04' => 'Excess Characters Error',
			'EC,E06' => 'Format Error',
			'EC,E07' => 'Parameter Setting Error',
			'EC,E11' => 'Stability Error',
			'EC,E20' => 'Calibration Weight Error - too heavy',
			'EC,E21' => 'Calibration Weight Error - too light',
		);
	}

}