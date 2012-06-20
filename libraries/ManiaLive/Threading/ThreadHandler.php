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

namespace ManiaLive\Threading;

use ManiaLive\Database\Connection;
use ManiaLive\Event\Dispatcher;
use ManiaLive\Features\Tick\Listener as TickListener;
use ManiaLive\Features\Tick\Event as TickEvent;
use ManiaLive\Utilities\Console;
use ManiaLive\Utilities\Logger;

final class ThreadHandler extends \ManiaLib\Utils\Singleton implements TickListener
{
	private $threadsCount = 0;
	
	private $threads = array();
	private $lastTick = array();
	private $buffers = array();
	private $pendings = array();
	private $tries = array();
	
	private $database;
	private $logger;
	private $tick = 0;
	
	private $enabled = false;
	private $deadThreadsCount = 0;
	private $commandsCount = 0;
	private $commandsTotalTime = 0;
	private $commandsAverageTime = 0;
	
	protected function __construct()
	{
		$this->logger = Logger::getLog('Threading_'.getmypid());
		
		if(! (extension_loaded('SQLite') || extension_loaded('SQLite3')) )
			$this->enabled = false;
		else
			$this->enabled = Config::getInstance()->enabled;
		
		if($this->enabled)
		{
			$this->cleanDirectory();
			$this->setUpDatabase();
			$this->setData('config',    \ManiaLive\Config\Config::getInstance());
			$this->setData('database',  \ManiaLive\Database\Config::getInstance());
			$this->setData('wsapi',     \ManiaLive\Features\WebServices\Config::getInstance());
			$this->setData('manialive', \ManiaLive\Application\Config::getInstance());
			$this->setData('server',    \ManiaLive\DedicatedApi\Config::getInstance());
			$this->setData('threading', \ManiaLive\Threading\Config::getInstance());
		}
		else
		{
			$this->logger->write('Application started with threading disabled!');
			$this->buffers[0] = array();
		}
		
		Dispatcher::register(TickEvent::getClass(), $this);
	}
	
	private function cleanDirectory()
	{
		$files = glob(APP_ROOT.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'threading_*.db'); 
		if(is_array($files))
		{
			$curTime = time();
			foreach($files as $file)
			{
				try
				{
					if($curTime - filemtime($file) > 3600)
						unlink($file);
				}
				catch (\Exception $e) {}
			}
		}
	}
	
	private function setUpDatabase()
	{
		$this->logger->write('Setting up Database...');
		
		$this->database = Connection::getConnection('threading_'.getmypid(), null, null, null, 'SQLite');
		if($this->database->tableExists('commands'))
			$this->database->execute('DELETE FROM commands;');
		else
			$this->database->execute(
					'CREATE TABLE commands '.
					'('.
						'commandId INTEGER PRIMARY KEY,'.
						'threadId INTEGER NULL,'.
						'task TEXT NOT NULL,'.
						'result TEXT NULL,'.
						'timeTaken REAL NULL'.
					');');
		
		if($this->database->tableExists('data'))
			$this->database->execute('DELETE FROM data;');
		else
			$this->database->execute(
					'CREATE TABLE data '.
					'('.
						'name TEXT NOT NULL,'.
						'value TEXT NULL,'.
						'PRIMARY KEY (name)'.
					');');
		
		
		sqlite_busy_timeout($this->database->getHandle(), 100);
		$this->logger->write('Database ready!');
	}
	
	function setData($key, $value)
	{
		$this->database->execute(
				'INSERT INTO data (name, value) VALUES (%s, %s)',
				$this->database->quote($key),
				$this->database->quote(serialize($value)));
		
		return $this->database->affectedRows() > 0;
	}
	
	function getData($key, $default=null)
	{
		$result = $this->database->query('SELECT value FROM data WHERE name=%s', $this->database->quote($key));
			
		if($result->recordAvailable())
			return unserialize($result->fetchScalar());
		else
			return $default;
	}
	
	function launchThread()
	{
		if(!$this->enabled)
			return 0;
		
		$threadId = ++$this->threadsCount;
		$threadHandle = $this->spawnThread($threadId);
		if($threadHandle === false)
			throw new Exception('Thread #'.$threadId.' could not be started!');
		
		$this->threads[$threadId] = $threadHandle;
		$this->lastTick[$threadId] = $this->tick;
		$this->buffers[$threadId] = array();
		$this->pendings[$threadId] = array();
		Dispatcher::dispatch(new Event(Event::ON_THREAD_START, $threadId));	

		return $threadId;
	}
	
	private function spawnThread($threadId)
	{
		$config = \ManiaLive\Config\Config::getInstance();
		$outputFile = $config->logsPath.DIRECTORY_SEPARATOR.$config->logsPrefix.'log_Threading_'.getmypid().'_'.$threadId.'.txt';
		$descriptors = array(
			1 => array('file', $outputFile, 'a'),
			2 => array('file', $outputFile, 'a')
		);
		
		$command = '"'.Config::getInstance()->phpPath.'" '.
			'"'.__DIR__.DIRECTORY_SEPARATOR.'thread_ignitor.php" '.
			$threadId.' '.getmypid();
		
		$this->logger->write('Trying to spawn Thread #'.$threadId.' using command:'.PHP_EOL.$command);
		return proc_open($command, $descriptors, $pipes, null, null, array('bypass_shell' => true));
	}
	
	function killThread($threadId)
	{
		if(!$this->enabled || !isset($this->threads[$threadId]))
			return;
		
		$threadHandle = $this->threads[$threadId];
		proc_terminate($threadHandle);
		proc_close($threadHandle);
		Dispatcher::dispatch(new Event(Event::ON_THREAD_KILLED, $threadId));	
		
		$this->database->execute('DELETE FROM commands WHERE threadId=%d', $threadId);
		
		unset($this->threads[$threadId]);
		unset($this->lastTick[$threadId]);
		unset($this->buffers[$threadId]);
		foreach($this->pendings[$threadId] as $commandId => $command)
			unset($this->tries[$commandId]);
		unset($this->pendings[$threadId]);
	}
	
	private function restartThread($threadId)
	{
		if(!$this->enabled || !isset($this->threads[$threadId]))
			return;
		
		if(empty($this->pendings[$threadId]))
			Dispatcher::dispatch(new Event(Event::ON_THREAD_DIES, $threadId));	
		else
		{
			Dispatcher::dispatch(new Event(Event::ON_THREAD_TIMES_OUT, $threadId));	
			// If we already tried this command too many times, we discard it...
			$lastCommandId = reset($this->pendings[$threadId])->getId();
			if(++$this->tries[$lastCommandId] > Config::getInstance()->maxTries)
			{
				$this->database->execute('DELETE FROM commands WHERE commandId=%d', $lastCommandId);
				unset($this->pendings[$threadId][$lastCommandId]);
				unset($this->tries[$lastCommandId]);
				$this->logger->write('Command #'.$lastCommandId.' has been discarded after '.Config::getInstance()->maxTries.' unsuccessful tries...');
			}
		}
		
		// Respawning the thread
		$threadHandle = $this->threads[$threadId];
		proc_terminate($threadHandle);
		proc_close($threadHandle);
		++$this->deadThreadsCount;
		$this->threads[$threadId] = $this->spawnThread($threadId);
		$this->lastTick[$threadId] = $this->tick;
		Dispatcher::dispatch(new Event(Event::ON_THREAD_RESTART, $threadId));	
	}
	
	function countThreads()
	{
		return count($this->threads);
	}
	
	function countRestartedThreads()
	{
		return $this->deadThreadsCount;
	}
	
	function addTask($threadId, Runnable $task, $callback=null)
	{
		if(isset($this->buffers[$threadId]))
			$this->buffers[$threadId][] = new Command($task, $callback);
	}
	
	function isEnabled()
	{
		return $this->enabled;
	}
	
	function countFinishedCommands()
	{
		return $this->commandsCount;
	}
	
	function getAverageResponseTime()
	{
		return $this->commandsAverageTime;
	}
	
	function onTick()
	{
		if($this->enabled)
		{
			++$this->tick;
			$this->receiveResponses();
			$this->handleTimeOuts();
			$this->sendTasks();
		}
		else
		{
			$startTime = microtime(true);
			$stopTime = $startTime + Config::getInstance()->sequentialTimeout;
			
			while($command = array_shift($this->buffers[0]))
			{
				$result = $command->getTask()->run();
				$endTime = microtime(true);
				$command->setResult($result, $endTime - $startTime);
				
				// stop after specified time
				if($endTime > $stopTime)
					break;
				
				$startTime = microtime(true);
			}
		}
	}
	
	private function receiveResponses()
	{
		try
		{
			$results = $this->database->query('SELECT commandId, threadId, result, timeTaken FROM commands WHERE result IS NOT NULL;');
		}
		catch (\Exception $ex)
		{
			if(strpos($ex->getMessage(), 'database is locked') === false)
				throw $ex;
			
			return;
		}
		
		if(!$results->recordAvailable())
			return;
		
		$ids = array();
		while($result = $results->fetchArray())
		{
			$commandId = (int) $result['commandId'];
			$threadId = (int) $result['threadId'];
			$timeTaken = (float) $result['timeTaken'];
			
			Console::printDebug('Got response for Command #'.$commandId.' finished by Thread #'.$threadId.' in '.$timeTaken.'ms!');
			
			if(isset($this->pendings[$threadId][$commandId]))
			{
				$command = $this->pendings[$threadId][$commandId];
				$command->setResult(unserialize($result['result']), $timeTaken);
				unset($this->pendings[$threadId][$commandId]);
				unset($this->tries[$commandId]);
			}
			if(isset($this->lastTick[$threadId]))
				$this->lastTick[$threadId] = $this->tick;
			
			++$this->commandsCount;
			$this->commandsTotalTime += $timeTaken;
			$this->commandsAverageTime = $this->commandsTotalTime / $this->commandsCount;
			
			$ids[] = $commandId;
		}
		
		$this->database->execute('DELETE FROM commands WHERE commandId IN (%s)', implode(',', $ids));
	}
	
	private function handleTimeOuts()
	{
		foreach($this->lastTick as $threadId => $tick)
		{
			if(empty($this->pendings[$threadId]) && $this->tick - $tick > Config::getInstance()->busyTimeout / 2)
			{
				$threadStatus = proc_get_status($this->threads[$threadId]);
				if($threadStatus['running'])
					$this->lastTick[$threadId] = $this->tick;
				else
					$this->restartThread($threadId);
			}
			else if($this->tick - $tick > Config::getInstance()->busyTimeout)
				$this->restartThread($threadId);
		}
	}
	
	private function sendTasks()
	{	
		$lines = array();
		foreach($this->buffers as $threadId => &$buffer)
			while($command = array_shift($buffer))
			{
				$commandId = $command->getId();
				$lines[] = sprintf('SELECT %d, %d, %s', $commandId, $threadId, $this->database->quote(serialize($command->getTask())));
				$this->pendings[$threadId][$commandId] = $command;
				$this->tries[$commandId] = 1;
			}
		
		if(!empty($lines))
			$this->database->execute('INSERT INTO commands(commandId, threadId, task) '.implode(' UNION ', $lines).';');
	}
	
	function __destruct()
	{
		foreach($this->threads as $thread)
			proc_terminate($thread);
	}
}

?>
