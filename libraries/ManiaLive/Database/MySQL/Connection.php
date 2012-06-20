<?php
/**
 * ManiaLive - TrackMania dedicated server manager in PHP
 * 
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace ManiaLive\Database\MySQL;

use ManiaLive\Database\SelectionException;
use ManiaLive\Database\NotSupportedException;
use ManiaLive\Database\QueryException;
use ManiaLive\Database\DisconnectionException;
use ManiaLive\Database\NotConnectedException;
use ManiaLive\Database\Exception;
use ManiaLive\Database\ConnectionException;
use ManiaLive\Event\Dispatcher;
use ManiaLive\Features\Tick\Event as TickEvent;
use ManiaLive\Features\Tick\Listener as TickListener;

class Connection extends \ManiaLive\Database\Connection implements TickListener
{
	protected $connection;
	protected $host;
	protected $user;
	protected $password;
	protected $database;
	
	protected $tick = 0;

	function __construct($host, $username, $password, $database, $port)
	{
		// Init
		$this->host = $host;
		$this->user = $username;
		$this->password = $password;
		$this->connect($database);
	}
	
	function onTick()
	{
		if($this->tick++ % 3600 == 0)
		{
			$this->execute('SELECT 1');
			$this->tick = 0;
		}
	}

	protected function connect($database)
	{
		// Connection
		try
		{
			$this->connection = mysql_connect(
					$this->host,
					$this->user,
					$this->password
			);
		}
		catch(\ErrorException $err)
		{
			throw new ConnectionException($err->getMessage(), $err->getCode());
		}

		// Success ?
		if(!$this->connection)
		{
			throw new ConnectionException;
		}

		$this->select($database);

		// Default Charset : UTF8
		$this->setCharset('utf8');
		Dispatcher::register(TickEvent::getClass(), $this);
	}

	/**
	 * @see ManiaLive\Database.Connection::getHandle()
	 * @return resource
	 */
	function getHandle()
	{
		return $this->connection;
	}

	function setCharset($charset)
	{
		if(function_exists('mysql_set_charset'))
		{
			if(!$this->isConnected())
			{
				$this->connect($this->database);
			}
			
			if(!mysql_set_charset($charset, $this->connection))
			{
				throw new Exception;
			}
		}
		else
		{
			$charset = $this->quote($charset);
			$this->execute('SET NAMES '.$charset);
		}
	}

	function select($database)
	{
		$this->database = $database;
		if(!mysql_select_db($this->database, $this->connection))
		{
			throw new SelectionException(mysql_error($this->connection), mysql_errno($this->connection));
		}
	}

	function quote($string)
	{
		if(!$this->isConnected())
		{
			$this->connect($this->database);
		}
		return '\''.mysql_real_escape_string($string, $this->connection).'\'';
	}

	/**
	 * @param string The query
	 * @return DatabaseRecordSet
	 */
	function query($query)
	{
		if(!$this->isConnected())
		{
			$this->connect($this->database);
		}
		Connection::startMeasuring($this);
		if(func_num_args() > 1)
		{
			$query = call_user_func_array('sprintf', func_get_args());
		}
		$result = mysql_query($query, $this->connection);
		Connection::endMeasuring($this);

		if(!$result)
		{
			throw new QueryException(mysql_error($this->connection), mysql_errno($this->connection));
		}
		return new RecordSet($result);
	}

	function execute($query)
	{
		if(!$this->isConnected())
		{
			$this->connect($this->database);
		}
		Connection::startMeasuring($this);
		if(func_num_args() > 1)
		{
			$query = call_user_func_array('sprintf', func_get_args());
		}
		$result = mysql_unbuffered_query($query);
		Connection::endMeasuring($this);

		if (!$result)
		{
			throw new QueryException(mysql_error($this->connection), mysql_errno($this->connection));
		}
	}

	function affectedRows()
	{
		if(!$this->isConnected())
		{
			$this->connect($this->database);
		}
		return mysql_affected_rows($this->connection);
	}

	function insertID()
	{
		if(!$this->isConnected())
		{
			$this->connect($this->database);
		}
		return mysql_insert_id($this->connection);
	}

	function isConnected()
	{
		return (bool)$this->connection;
	}

	function disconnect()
	{
		if(!mysql_close($this->connection))
		{
			throw new DisconnectionException;
		}
		$this->connection = null;
		Dispatcher::unregister(TickEvent::getClass(), $this);
	}

	function getDatabase()
	{
		return $this->database;
	}

	function tableExists($tableName)
	{
		$table = $this->query('SHOW TABLES LIKE '.$this->quote($tableName));
		return ($table->recordCount() > 0);
	}
}
?>