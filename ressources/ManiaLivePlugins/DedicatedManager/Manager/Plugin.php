<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace ManiaLivePlugins\DedicatedManager\Manager;

class Plugin extends \ManiaLive\PluginHandler\Plugin
{
	protected $tick;

	function onReady()
	{
		$this->enableTickerEvent();
		$generalConfig = \ManiaLive\Database\Config::getInstance();
		$localConfig = Config::getInstance();
		$this->db = \ManiaLive\Database\MySQL\Connection::getConnection(
				$generalConfig->host,
				$localConfig->username,
				$localConfig->password,
				$localConfig->database);
	}

	function onTick()
	{
		if($this->tick++ % 30 == 0)
		{
			$config = \ManiaLive\DedicatedApi\Config::getInstance();
			$infos = $this->connection->getSystemInfo();
			$onDuplicateKeyStr = implode(',', array_map(
					function ($c) { return $c.'=VALUES('.$c.')'; },
					array('login', 'name', 'joinIp', 'joinPort', 'joinPassword', 'specPassword', 'isRelay', 'lastLiveDate')
				));
			$this->db->execute(
					'INSERT INTO Servers (login, name, rpcHost, rpcPort, rpcPassword, joinIp, joinPort, joinPassword, specPassword, isRelay, lastLiveDate) '.
					'VALUES(%s, %s, %s, %d, %s, %s, %d, %s, %s, %d, NOW()) ON DUPLICATE KEY UPDATE '.$onDuplicateKeyStr,
					$this->db->quote($infos->serverLogin),
					$this->db->quote($this->connection->getServerName()),
					$this->db->quote($config->host),
					$config->port,
					$this->db->quote($config->password),
					$this->db->quote($infos->publishedIp),
					$infos->port,
					$this->db->quote($this->connection->getServerPassword()),
					$this->db->quote($this->connection->getServerPasswordForSpectator()),
					$this->db->quote($this->connection->isRelayServer())
			);
		}
	}
}

?>