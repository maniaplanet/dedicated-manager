<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class Spectate extends \ManiaLive\DedicatedApi\Structures\AbstractStructure
{
	const MANAGED = 0;
	const LOGIN = 1;
	const IP_AND_PORT = 2;
	
	public $method = self::MANAGED;
	public $managed = '';
	public $login = '';
	public $ip = '127.0.0.1';
	public $port = 2350;
	public $password = '';
	
	function getIdentifier()
	{
		switch($this->method)
		{
			case self::MANAGED:
				list($ip,$port) = explode(':', $this->managed);
				return $ip.':'.$port;
			case self::IP_AND_PORT:
				return $this->ip.':'.$this->port;
			case self::LOGIN:
				return $this->login;
		}
	}
	
	function getPassword()
	{
		if($this->method == self::MANAGED)
		{
			list(,,$password) = explode(':', $this->managed);
			return $password;
		}
		return $this->password;
	}
}

?>
