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
		$result = $this->db()->execute('SELECT * FROM Servers WHERE DATE_ADD(lastLiveDate, INTERVAL 1 MINUTE) > NOW()');
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
				'WHERE M.login = %s AND DATE_ADD(lastLiveDate, INTERVAL 1 MINUTE) > NOW()',
				$this->db()->quote($login));
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
				'SELECT * FROM Servers WHERE rpcHost=%s AND rpcPort=%d',
				$this->db()->quote($rpcHost),
				$rpcPort
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
		$startCommand .= sprintf(' /dedicated_cfg=%s /join=%s', escapeshellarg($configFile.'.txt'), escapeshellarg($spectate->getIdentifier()));
		if( ($password = $spectate->getPassword()) )
			$startCommand .= sprintf(' /joinpassword=%s', $password);
		if($isLan)
			$startCommand .= ' /lan';
		if(!$isWindows)
			$startCommand .= ' &';

		$this->doStart($startCommand, 'Synchronizing...');
	}
	
	function startNoautoquit($configFile=null)
	{
		$config = \DedicatedManager\Config::getInstance();

		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			$startCommand = 'START /D "'.$config->dedicatedPath.'" ManiaPlanetServer.exe';
		else
			$startCommand = 'cd "'.$config->dedicatedPath.'"; ./ManiaPlanetServer';
		$startCommand .= ($configFile ? sprintf(' /dedicated_cfg=%s', escapeshellarg($configFile)) : '').' /noautoquit';
		if(!$isWindows)
			$startCommand .= ' &';

		$this->doStart($startCommand, 'Ready, waiting for commands.');
	}
	
	private function doStart($commandLine, $successStr='...Load succeeds')
	{
		$config = \DedicatedManager\Config::getInstance();

		// Getting current PIDs
		$currentPids = $this->getPIDs();
		
		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		$procHandle = proc_open($commandLine, array(), $pipes, $config->dedicatedPath);
		proc_close($procHandle);

		// Getting its PID
		$diffPids = array_diff($this->getPIDs(), $currentPids);
		if(!$diffPids)
			throw new \Exception('Can\'t start dedicated server.');
		$pid = reset($diffPids);

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
				if(strpos($buffer, $successStr) !== false)
					break;
				if(strpos($buffer, 'Server not running, exiting.') !== false || strpos($buffer, 'This title isn\'t playable.') !== false)
					throw new \Exception('Server stopped automatically');
				
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