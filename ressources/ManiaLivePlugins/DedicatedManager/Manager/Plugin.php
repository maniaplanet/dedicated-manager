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

	/**
	 * @var \ManiaLive\Database\MySQL\Connection
	 */
	protected $db;

	function onReady()
	{
		$this->enableTickerEvent();
		$generalConfig = \ManiaLive\Database\Config::getInstance();
		$localConfig = Config::getInstance();
		$this->db = \ManiaLive\Database\MySQL\Connection::getConnection($generalConfig->host,
						$localConfig->username, $localConfig->password, $localConfig->database);
	}

	function onTick()
	{
		if($this->tick++ % 30 == 0)
		{
			$config = \ManiaLive\DedicatedApi\Config::getInstance();
			$this->db->execute(
					'INSERT INTO Servers (hostname,port,password,name,lastLiveDate) '.
					'VALUES (%s,%d,%s,%s,NOW()) ON DUPLICATE KEY UPDATE name = VALUES(name), lastLiveDate = NOW()',
					$this->db->quote($config->host), $config->port,
					$this->db->quote($config->password),
					$this->db->quote($this->storage->server->name)
			);
		}
	}

}

?>