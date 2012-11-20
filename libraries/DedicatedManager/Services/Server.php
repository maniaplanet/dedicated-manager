<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class Server extends AbstractObject
{
	/** @var string */
	public $login;
	/** @var string */
	public $name;
	/** @var string */
	public $titleId;
	/** @var string */
	public $rpcHost;
	/** @var int */
	public $rpcPort;
	/** @var string */
	public $rpcPassword;
	/** @var string */
	public $joinIp;
	/** @var int */
	public $joinPort;
	/** @var string */
	public $joinPassword;
	/** @var string */
	public $specPassword;
	/** @var bool */
	public $isRelay;
	
	/** @var \DedicatedApi\Connection */
	public $connection;
	
	function openConnection()
	{
		$this->connection = \DedicatedApi\Connection::factory($this->rpcHost, $this->rpcPort, 5, 'SuperAdmin', $this->rpcPassword);
	}
	
	function closeConnection()
	{
		\DedicatedApi\Connection::delete($this->rpcHost, $this->rpcPort);
	}
	
	function fetchDetails()
	{
		$this->openConnection();
		
		$serverName = $this->connection->getServerName();
		if($serverName != $this->name)
		{
			$service = new ServerService();
			$service->register($this);
			$this->name = $serverName;
		}
		$info = $this->connection->getSystemInfo();
		$this->login = $info->serverLogin;
		$this->titleId = $info->titleId;
		$this->joinIp = $info->publishedIp;
		$this->joinPort = $info->port;
		$this->joinPassword = $this->connection->getServerPassword();
		$this->specPassword = $this->connection->getServerPasswordForSpectator();
		$this->isRelay = $this->connection->isRelayServer();
		$this->connection = $this->connection;
	}
	
	/**
	 * @return string
	 */
	function getLink($method='join')
	{
		$isLan = preg_match('/_\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}_\d{1,5}/', $this->login);
		$password = preg_match('/^q?join$/i', $method) ? $this->joinPassword : $this->specPassword;
		return 'maniaplanet://#'.$method.'='.($isLan ? $this->joinIp : $this->login).($password ? ':'.$password : '').'@'.$this->titleId;
	}
}

?>