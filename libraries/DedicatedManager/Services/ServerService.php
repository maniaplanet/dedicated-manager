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
	protected $databaseName = 'Manager';

	/**
	 * @return Server[]
	 */
	function getLives()
	{
		$result = $this->db()->execute('SELECT * FROM Servers WHERE DATE_ADD(lastLiveDate, INTERVAL 1 MINUTE) > NOW()');
		return Server::arrayFromRecordSet($result);
	}

	/**
	 * @param string $hostname
	 * @param int $port
	 * @return Server
	 */
	function get($hostname, $port)
	{
		$result = $this->db()->execute(
				'SELECT * FROM Servers '.
				'WHERE hostname=%s AND port=%d', $this->db()->quote($hostname), $port
		);
		return Server::fromRecordSet($result);
	}

	/**
	 * @param string $hostname
	 * @param int $port
	 */
	function delete($hostname, $port)
	{
		$this->db()->execute('DELETE FROM Servers WHERE hostname=%s AND port=%d', $this->db()->quote($hostname), $port);
	}

	/**
	 * @param string $configFile
	 * @param string $matchFile
	 * @param bool $isLan
	 */
	function start($configFile, $matchFile, $isLan)
	{
		$config = \DedicatedManager\Config::getInstance();

		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			$startCommand = 'START /D "'.$config->dedicatedPath.'" ManiaPlanetServer.exe';
		else
			$startCommand = 'cd "'.$config->dedicatedPath.'"; ./ManiaPlanetServer';
		$startCommand .= sprintf(' /dedicated_cfg=%s /game_settings=%s', escapeshellarg($configFile.'.txt'), escapeshellarg('MatchSettings/'.$matchFile.'.txt'));
		if($isLan)
			$startCommand .= ' /lan';
		if(!$isWindows)
			$startCommand .= ' &';

		$this->doStart($startCommand);
	}
	
	/**
	 * @param string $configFile
	 * @param Spectate $spectate
	 * @param bool $isLan
	 */
	function startRelay($configFile, Spectate $spectate, $isLan)
	{
		$config = \DedicatedManager\Config::getInstance();

		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			$startCommand = 'START /D "'.$config->dedicatedPath.'" ManiaPlanetServer.exe';
		else
			$startCommand = 'cd "'.$config->dedicatedPath.'"; ./ManiaPlanetServer';
		$startCommand .= sprintf(' /dedicated_cfg=%s /join=%s', escapeshellarg($configFile.'.txt'), $spectate);
		if($isLan)
			$startCommand .= ' /lan';
		if(!$isWindows)
			$startCommand .= ' &';

		$this->doStart($startCommand);
	}
	
	private function doStart($commandLine)
	{
		$config = \DedicatedManager\Config::getInstance();

		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		$procHandle = proc_open($commandLine, array(), $pipes, $config->dedicatedPath);
		proc_close($procHandle);

		// Getting its PID
		if($isWindows)
		{
			$dedicatedProc = `TASKLIST /FI "IMAGENAME eq ManiaPlanetServer.exe" /FI "CPUTIME eq 00:00:00" /NH`;
			if(!preg_match('/ManiaPlanetServer\.exe\s+(\d+)/m', $dedicatedProc, $matches))
				throw new \Exception('Can\'t start dedicated server.');
			$pid = $matches[1];
		}
		else
		{
			$dedicatedProc = `ps -C "ManiaPlanetServer" --format pid,cputime --no-headers --sort +cputime`;
			if(!preg_match('/(\\d+)\s+(?:00-)?00:00:00/', $dedicatedProc, $matches))
				throw new \Exception('Can\'t start dedicated server.');
			$pid = $matches[1];
		}

		// Reading dedicated log while it's written
		$logFileName = $config->dedicatedPath.'Logs/ConsoleLog.'.$pid.'.txt';
		while(!file_exists($logFileName))
			usleep(200000);
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
				if(strpos($buffer, '...Load succeeds') !== false || strpos($buffer, 'exiting') !== false)
					break;
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
		if(preg_match_all('/ERROR:\s+([^\.$]+)/um', $buffer, $errors) || strpos($buffer, '...Server stopped') !== false)
		{
			if($isWindows)
				`TASKKILL /PID $pid`;
			else
				`kill -9 $pid`;
			
			if(!$errors)
			{
				$errors[1] = 'Server stopped automatically';
			}

			throw new \Exception(serialize(array_map('ucfirst', $errors[1])));
		}

		// Retrieving XML-RPC port
		if(preg_match('/Listening for xml-rpc commands on port (\d+)/um', $buffer, $matches))
				$port = $matches[1];
		else
			throw new \Exception('XML-RPC port not found');

		// Registering server and starting ManiaLive
		$this->startManiaLive($port, $pid);
	}

	function startManiaLive($port, $dedicatedPid = 0)
	{
		$config = \DedicatedManager\Config::getInstance();
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
				$startCommand = 'START php.exe "'.$config->manialivePath.'bootstrapper.php"';
		else
			$startCommand = 'cd "'.$config->manialivePath.'"; php bootstrapper.php';
		$startCommand .= sprintf(' --rpcport=%d', $port);
		if(!$isWindows)
			$startCommand .= ' < /dev/null > logs/runtime.'.$dedicatedPid.'.log 2>&1 &';

		sleep(5);

		$procHandle = proc_open($startCommand, array(), $pipes);
		proc_close($procHandle);
	}

}

?>