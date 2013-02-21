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
	/** @var _ConfigPart */
	public $config;
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
		$this->config = new _ConfigPart();
		$this->database = new _DatabasePart();
		$this->threading = new _ThreadingPart();
		$this->wsapi = new _WsApiPart();
	}
	
	/**
	 * @param string[] $array
	 */
	function setConfigFromArray($array)
	{
		$this->config = _ConfigPart::fromArray($array);
	}
	
	/**
	 * @param string[] $array
	 */
	function setDatabaseFromArray($array)
	{
		$this->database = _DatabasePart::fromArray($array);
	}
	
	/**
	 * @param string[] $array
	 */
	function setThreadingFromArray($array)
	{
		$this->threading = _ThreadingPart::fromArray($array);
	}
	
	/**
	 * @param string[] $array
	 */
	function setWsApiFromArray($array)
	{
		$this->wsapi = _WsApiPart::fromArray($array);
	}
}

class _ConfigPart extends \DedicatedApi\Structures\AbstractStructure
{
	/** @var string */
	public $logsPath = '';
	/** @var string */
	public $logsPrefix = 'manialive';
	/** @var bool */
	public $runtimeLog = false;
	/** @var bool */
	public $globalErrorLog = false;
	/** @var bool */
	public $debug = false;
	/** @var bool */
	public $verbose = true;
}

class _DatabasePart extends \DedicatedApi\Structures\AbstractStructure
{
	/** @var bool */
	public $enable = true;
	/** @var string */
	public $host = '127.0.0.1';
	/** @var int */
	public $port = 3306;
	/** @var string */
	public $username = 'root';
	/** @var string */
	public $password = '';
	/** @var string */
	public $database = 'ManiaLive';
}

class _ThreadingPart extends \DedicatedApi\Structures\AbstractStructure
{
	/** @var bool */
	public $enabled = false;
	/** @var int */
	public $busyTimeout = 20;
	/** @var int */
	public $maxTries = 3;
}

class _WsApiPart extends \DedicatedApi\Structures\AbstractStructure
{
	/** @var string */
	public $username = '';
	/** @var string */
	public $password = '';
}

?>
