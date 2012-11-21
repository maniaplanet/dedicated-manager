<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class ServerService extends AbstractService
{
	/**
	 * @return Server[]
	 */
	function getLives()
	{
		$result = $this->db()->execute('SELECT * FROM Servers');
		return Server::arrayFromRecordSet($result);
	}

	/**
	 * @param string $login
	 * @return Server[]
	 */
	function getLivesForManager($login)
	{
		$result = $this->db()->execute(
				'SELECT S.* FROM Managers M INNER JOIN Servers S USING (rpcHost,rpcPort) '.
				'WHERE M.login = %s', $this->db()->quote($login)
			);
		return Server::arrayFromRecordSet($result);
	}

	/**
	 * @param string $rpcHost
	 * @param int $rpcPort
	 * @return Server
	 */
	function get($rpcHost, $rpcPort)
	{
		$result = $this->db()->execute(
				'SELECT * FROM Servers WHERE rpcHost=%s AND rpcPort=%d', $this->db()->quote($rpcHost), $rpcPort
			);
		return Server::fromRecordSet($result);
	}

	/**
	 * @param string $rpcHost
	 * @param int $rpcPort
	 */
	function delete($rpcHost, $rpcPort)
	{
		$this->db()->execute('DELETE FROM Servers WHERE rpcHost=%s AND rpcPort=%d', $this->db()->quote($rpcHost), $rpcPort);
	}

	/**
	 * @param string $configFile
	 * @param string $matchFile
	 * @param bool $isLan
	 * @return int
	 */
	function start($configFile, $matchFile, $isLan = false, $options=array())
	{
		$options['dedicated_cfg'] = $configFile.'.txt';
		$options['game_settings'] = 'MatchSettings/'.$matchFile.'.txt';
		$options['lan'] = $isLan;
		$startCommand = $this->prepareCommandLine($options);

		return $this->doStart($startCommand);
	}

	/**
	 * @param string $configFile
	 * @param string $server
	 * @param string $password
	 * @param bool $isLan
	 */
	function startRelay($configFile, $server, $password=null, $isLan=false, $options=array())
	{
		$options['dedicated_cfg'] = $configFile.'.txt';
		$options['join'] = $server;
		if($password)
			$options['joinpassword'] = $password;
		$options['lan'] = $isLan;
		$startCommand = $this->prepareCommandLine($options);

		return $this->doStart($startCommand, 'Synchro');
	}
	
	/**
	 * @param mixed[] $options
	 * @return string
	 */
	private function prepareCommandLine($options)
	{
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			$cmd = 'START ManiaPlanetServer.exe';
		else
			$cmd = './ManiaPlanetServer';
		
		foreach($options as $key => $value)
		{
			if(!is_bool($value))
				$cmd .= ' /'.$key.'='.escapeshellarg($value);
			else if($value)
				$cmd .= ' /'.$key;
		}
		
		if(!$isWindows)
			$cmd .= ' &';
		
		return $cmd;
	}

	/**
	 * @param string $commandLine
	 * @param string $successStr
	 * @return int
	 * @throws \Exception
	 */
	private function doStart($commandLine, $successStr = '...Load succeeds')
	{
		$config = \DedicatedManager\Config::getInstance();
		$time = time();
		$timeout = $time + 20;

		// Getting current PIDs
		$currentPids = $this->getPIDs();

		// Starting dedicated
		$procHandle = proc_open($commandLine, array(), $pipes, $config->dedicatedPath);
		proc_close($procHandle);

		// Getting its PID
		$diffPids = array_diff($this->getPIDs(), $currentPids);
		if(!$diffPids)
			throw new \Exception('Can\'t start dedicated server.');
		$pid = reset($diffPids);

		// Reading dedicated log while it's written
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		$logFileName = $config->dedicatedPath.'Logs/ConsoleLog.'.$pid.'.txt';
		while(!file_exists($logFileName) || filemtime($logFileName) < $time)
		{
			if(time() > $timeout)
				throw new \Exception('Can\'t start dedicated server.');
			usleep(200000);
		}
		$tries = 0;
		while(!($logFile = fopen($logFileName, 'r')))
		{
			if(++$tries == 5)
			{
				if($isWindows)
					`TASKKILL /PID $pid`;
				else
					`kill -9 $pid`;

				throw new \Exception('Unknown error while trying to get XML-RPC port');
			}
		}
		$buffer = '';
		while(true)
		{
			$line = fgets($logFile);
			if(!$line)
			{
				if(strpos($buffer, $successStr) !== false)
					break;
				if(strpos($buffer, 'Server not running, exiting.') !== false || strpos($buffer, 'This title isn\'t playable.') !== false)
					throw new \Exception('Server stopped automatically');
				if(time() > $timeout)
				{
					if($isWindows)
						`TASKKILL /PID $pid`;
					else
						`kill -9 $pid`;
					throw new \Exception('Server stopped automatically');
				}

				if(!$buffer)
					fseek($logFile, 0, SEEK_SET);
				else
					fseek($logFile, -1, SEEK_CUR);
				usleep(200000);
				continue;
			}
			if($line !== "\n")
				$buffer .= $line;
		}
		fclose($logFile);

		// Checking for errors
		if(preg_match_all('/ERROR:\s+([^\.$]+)/um', $buffer, $errors))
		{
			if($isWindows)
				`TASKKILL /PID $pid`;
			else
				`kill -9 $pid`;

			throw new \Exception(serialize(array_map('ucfirst', $errors[1])));
		}

		// Retrieving XML-RPC port
		if(preg_match('/Listening for xml-rpc commands on port (\d+)/m', $buffer, $matches))
			$port = $matches[1];
		else
			throw new \Exception('XML-RPC port not found');

		return $port;
	}

	/**
	 * @param Server $server
	 */
	function register($server)
	{
		$server->openConnection();

		$this->db()->execute(
				'INSERT INTO Servers(name, rpcHost, rpcPort, rpcPassword) VALUES (%s,%s,%d,%s) '.
				'ON DUPLICATE KEY UPDATE name=VALUES(name), rpcPassword=VALUES(rpcPassword)',
				$this->db()->quote($server->connection->getServerName()),
				$this->db()->quote($server->rpcHost),
				$server->rpcPort,
				$this->db()->quote($server->rpcPassword)
			);
	}

	/**
	 * @return int[]
	 */
	private function getPIDs()
	{
		if(stripos(PHP_OS, 'WIN') === 0)
		{
			$dedicatedProc = `TASKLIST /FI "IMAGENAME eq ManiaPlanetServer.exe" /NH`;
			if(preg_match_all('/ManiaPlanetServer\.exe\s+(\d+)/m', $dedicatedProc, $matches))
				return $matches[1];
		}
		else
		{
			$dedicatedProc = `ps -C "ManiaPlanetServer" --format pid --no-headers --sort +cputime`;
			if(preg_match_all('/(\\d+)/', $dedicatedProc, $matches))
				return $matches[1];
		}
		return array();
	}
}

?>