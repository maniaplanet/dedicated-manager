<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

class ScriptService
{

	function getActions($host, $port) 
	{
		$service = new ServerService();
		$server = $service->get($host, $port);
		$connection = \DedicatedApi\Connection::factory($host, $port, 5, 'SuperAdmin', $server->rpcPassword);
		$infos = $connection->getModeScriptInfo();
		$commandsArray = $infos->commandDescs;
		$commands = array();
		foreach($commandsArray as $elem)
		{
			$obj = new Command();
			$obj->name = $elem['Name'];
			$obj->description = $elem['Desc'];
			$obj->type = $elem['Type'];
			$obj->default = $elem['Default'];
			$commands[$obj->name] = $obj;
		}		
		return $commands;
	}

}

?>
