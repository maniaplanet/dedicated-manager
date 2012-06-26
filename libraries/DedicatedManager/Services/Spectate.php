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
	public $managedLogin = '';
	public $login = '';
	public $ip = '127.0.0.1';
	public $port = 2350;
	
	function __toString()
	{
		switch($this->method)
		{
			case self::MANAGED:
				if(preg_match('/_(\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})_(\d{1,5})$/', $this->managedLogin, $matches))
					return sprintf('%s:%s', $matches[1], $matches[2]);
				return $this->managedLogin;
			case self::IP_AND_PORT:
				return sprintf('%s:%s', $this->ip, $this->port);
			case self::LOGIN:
				return $this->login;
		}
		return '';
	}
}

?>
