<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class ManialiveConfig
{
	/** @var _LogsPart */
	public $logs;
	/** @var _DatabasePart */
	public $database;
	/** @var _ThreadingPart */
	public $threading;
	/** @var _WsApiPart */
	public $wsapi;
	/** @var string[] */
	public $admins = array();
	/** @var string[] */
	public $plugins = array();
	/** @var string */
	public $__other = '';
	
	function __construct()
	{
		$this->logs = new _LogsPart();
		$this->database = new _DatabasePart();
		$this->threading = new _ThreadingPart();
		$this->wsapi = new _WsApiPart();
	}
	
	function setLogsFromArray($array)
	{
		$this->logs = _LogsPart::fromArray($array);
	}
	
	function setDatabaseFromArray($array)
	{
		$this->database = _DatabasePart::fromArray($array);
	}
	
	function setThreadingFromArray($array)
	{
		$this->threading = _ThreadingPart::fromArray($array);
	}
	
	function setWsApiFromArray($array)
	{
		$this->wsapi = _WsApiPart::fromArray($array);
	}
}

class _LogsPart extends \DedicatedApi\Structures\AbstractStructure
{
	public $logsPath = '';
	public $logsPrefix = 'manialive';
	public $runtimeLog = false;
	public $globalErrorLog = false;
}

class _DatabasePart extends \DedicatedApi\Structures\AbstractStructure
{
	public $enable = true;
	public $host = '127.0.0.1';
	public $port = 3306;
	public $username = 'root';
	public $password = '';
	public $database = 'ManiaLive';
}

class _ThreadingPart extends \DedicatedApi\Structures\AbstractStructure
{
	public $enabled = false;
	public $busyTimeout = 20;
	public $maxTries = 3;
}

class _WsApiPart extends \DedicatedApi\Structures\AbstractStructure
{
	public $username = '';
	public $password = '';
}

?>
