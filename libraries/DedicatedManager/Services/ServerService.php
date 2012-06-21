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
		$result = $this->db()->execute(
				'SELECT * FROM Servers '.
				'WHERE DATE_ADD(lastLiveDate,INTERVAL 1 MINUTE) > NOW()'
		);

		return Server::arrayFromRecordSet($result);
	}

	function deleteConfigFileList(array $files)
	{
		$configDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Config/';
		foreach($files as $file)
		{
			if(file_exists($configDirectory.$file.'.txt'))
			{
				unlink($configDirectory.$file.'.txt');
			}
		}
	}

	/**
	 * @return array
	 */
	function getConfigFileList()
	{
		$configDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Config/';
		if(!file_exists($configDirectory))
		{
			return array();
		}
		$currentDir = getcwd();
		chdir($configDirectory);

		$configFiles = array();
		foreach(glob('*.[tT][xX][tT]') as $file)
		{
			$content = file_get_contents($configDirectory.$file, false, null, 0, 100);
			if(stripos($content, '<dedicated>') !== false)
			{
				$configFiles[] = stristr($file, '.txt', true);
			}
		}
		chdir($currentDir);
		return $configFiles;
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
				'WHERE hostname = %s AND port = %d', $this->db()->quote($hostname), $port
		);
		return Server::fromRecordSet($result);
	}

	/**
	 * @param string $filename
	 * @return \ManiaLive\DedicatedApi\Structures\ServerOptions
	 * @throws \InvalidArgumentException
	 */
	function getConfig($filename)
	{
		$configDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Config/';

		if(!file_exists($configDirectory.$filename.'.txt'))
		{
			throw new \InvalidArgumentException('File does not exists');
		}

		$configObj = simplexml_load_file($configDirectory.$filename.'.txt');

		$config = new ServerOptions();
		$config->name = (string) $configObj->server_options->name;
		$config->comment = (string) $configObj->server_options->comment;
		$config->password = (string) $configObj->server_options->password;
		$config->passwordForSpectator = (string) $configObj->server_options->password_spectator;
		$config->nextMaxPlayers = (int) $configObj->server_options->max_players;
		$config->nextMaxSpectators = (int) $configObj->server_options->max_spectators;
		$config->hideServer = (int) $configObj->server_options->hide_server;
		$config->isP2PUpload = $this->toBool($configObj->server_options->enable_p2p_upload);
		$config->isP2PDownload = $this->toBool($configObj->server_options->enable_p2p_download);
		$config->nextLadderMode = (string) $configObj->server_options->ladder_mode;
		$config->nextLadderMode = ($config->nextLadderMode == 1 || $config->nextLadderMode == 'forced'
							? 1 : 0);
		$config->ladderServerLimitMax = (int) $configObj->server_options->ladder_serverlimit_max;
		$config->ladderServerLimitMin = (int) $configObj->server_options->ladder_serverlimit_min;
		$config->nextCallVoteTimeOut = (int) $configObj->server_options->callvote_timeout;
		$config->callVoteRatio = (double) $configObj->server_options->callvote_ratio;
		$config->allowMapDownload = $configObj->server_options->allow_map_download == 'True';
		$config->autoSaveReplays = $configObj->server_options->autosave_replays == 'True';
		$config->autoSaveValidationReplays = $this->toBool($configObj->server_options->autosave_validation_replays);
		$config->refereePassword = (string) $configObj->server_options->referee_password;
		$config->refereeMode = (string) $configObj->server_options->referee_validation_mode;
		$config->nextUseChangingValidationSeed = $configObj->server_options->use_changing_validation_seed == 'True';
		
		$system = new SystemConfig();
		$system->connectionUploadrate = (int)$configObj->system_config->connection_uploadrate;
		$system->connectionDownloadrate = (int) $configObj->system_config->connection->downnloadrate;
		$system->allowSpectatorRelays = $configObj->system_config->allow_spectator_relays == 'True';
		$system->p2pCacheSize = (int) $configObj->system_config->p2p_cache_size;
		$system->forceIpAddress = (string) $configObj->system_config->force_ip_address;
		$system->serverPort = (int) $configObj->system_config->server_port;
		$system->serverP2pPort = (int) $configObj->system_config->server_p2p_port;
		$system->clientPort = (int) $configObj->system_config->clientPort;
		$system->bindIpAddress = (string) $configObj->system_config->bind_ip_address;
		$system->useNatUpnp = $configObj->system_config->use_nat_upnp == 'True';
		$system->xmlrpcPort = (int) $configObj->system_config->xmlrpc_port;
		$system->xmlrpcAllowremote = (string) $configObj->system_config->xmlrpc_allowremote;
		$system->blacklistUrl = (string) $configObj->system_config->blacklist_url;
		$system->guestlistFilename = (string) $configObj->system_config->guestlist_filename;
		$system->blacklistFilename = (string) $configObj->system_config->blacklist_filename;
		$system->title = (string) $configObj->system_config->title;
		$system->minimumClientBuild = (string) $configObj->system_config->minimum_client_build;
		$system->disableCoherenceChecks = $this->toBool($configObj->system_config->disable_coherence_checks);
		$system->useProxy = $configObj->system_config->use_proxy == 'True';
		$system->proxyLogin = (string) $configObj->system_config->proxy_login;
		$system->proxyPassword = (string) $configObj->system_config->proxyPassword;

		$account = new Account();
		$account->login = (string) $configObj->masterserver_account->login;
		$account->password = (string) $configObj->masterserver_account->password;
		$account->validationKey = (string) $configObj->masterserver_account->validation_key;

		return array($config, $account, $system);
	}

	/**
	 * @param string $filename
	 * @param ServerOptions $config
	 */
	function save($filename, ServerOptions $config, Account $account = null, SystemConfig $system = null)
	{
		$configDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Config/';

		$dom = new \DOMDocument('1.0', 'utf-8');
		$dedicated = simplexml_import_dom($dom->createElement('dedicated'));

		$authLevel = $dedicated->addChild('authorization_levels');
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'SuperAdmin');
		$level->addChild('password', 'SuperAdmin');
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'Admin');
		$level->addChild('password', 'Admin');
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'User');
		$level->addChild('password', 'User');

		//TODO Find a way to handle internet server
		if(!$account)
		{
			$account = new Account();
		}
		$masterAccount = $dedicated->addChild('masterserver_account');
		$masterAccount->addChild('login', (string)$account->login);
		$masterAccount->addChild('password', (string)$account->password);
		$masterAccount->addChild('validation_key', (string)$account->validationKey);

		$server_options = $dedicated->addChild('server_options');
		$server_options->addChild('name', (string) $config->name);
		$server_options->addChild('comment', (string) $config->comment);
		$server_options->addChild('hide_server', (int) $config->hideServer);
		$server_options->addChild('max_players', (int) $config->nextMaxPlayers);
		$server_options->addChild('password', (string) $config->password);
		$server_options->addChild('max_spectators', (int) $config->nextMaxSpectators);
		$server_options->addChild('password_spectator',
				(string) $config->passwordForSpectator);
		$server_options->addChild('ladder_mode', (int) $config->nextLadderMode);
		$server_options->addChild('ladder_serverlimit_min',
				(int) $config->ladderServerLimitMin);
		$server_options->addChild('ladder_serverlimit_max',
				(int) $config->ladderServerLimitMax);
		$server_options->addChild('enable_p2p_upload',
				($config->isP2PUpload ? 'True' : 'False'));
		$server_options->addChild('enable_p2p_download',
				($config->isP2PDownload ? 'True' : 'False'));
		$server_options->addChild('callvote_timeout',
				(int) $config->nextCallVoteTimeOut);
		$server_options->addChild('callvote_ratio', (int) $config->callVoteRatio);
		$server_options->addChild('allow_map_download',
				($config->allowMapDownload ? 'True' : 'False'));
		$server_options->addChild('autosave_replays',
				($config->autoSaveReplays ? 'True' : 'False'));
		$server_options->addChild('autosave_validation_replays',
				($config->autoSaveValidationReplays ? 'True' : 'False'));
		$server_options->addChild('referee_password',
				(string) $config->refereePassword);
		$server_options->addChild('referee_validation_mode',
				(string) $config->refereeMode);
		$server_options->addChild('use_changing_validation_seed',
				(string) $config->nextUseChangingValidationSeed);

		//TODO find a wat to change system settings if needed
		if(!$system)
		{
			$system = new SystemConfig();
		}
		
		$systemConfig = $dedicated->addChild('system_config');
		$systemConfig->addChild('connection_uploadrate', $system->connectionUploadrate);
		$systemConfig->addChild('connection_downloadrate', $system->connectionDownloadrate);

		$systemConfig->addChild('allow_spectator_relays', ($system->allowSpectatorRelays ? 'True' : 'False'));

		$systemConfig->addChild('p2p_cache_size', $system->p2pCacheSize);

		$systemConfig->addChild('force_ip_address', $system->forceIpAddress);
		$systemConfig->addChild('server_port', $system->serverPort);
		$systemConfig->addChild('server_p2p_port', $system->serverP2pPort);
		$systemConfig->addChild('client_port', $system->clientPort);
		$systemConfig->addChild('bind_ip_address', $system->bindIpAddress);
		$systemConfig->addChild('use_nat_upnp', ($system->useNatUpnp ? 'True' : 'False'));

		$systemConfig->addChild('xmlrpc_port', $system->xmlrpcPort);
		$systemConfig->addChild('xmlrpc_allowremote', $system->xmlrpcAllowremote);

		$systemConfig->addChild('blacklist_url', $system->blacklistUrl);
		$systemConfig->addChild('guestlist_filename', $system->guestlistFilename);
		$systemConfig->addChild('blacklist_filename', $system->blacklistFilename);
		$systemConfig->addChild('title', $system->title);

		$systemConfig->addChild('minimum_client_build', $system->minimumClientBuild);

		$systemConfig->addChild('disable_coherence_checks', ($system->disableCoherenceChecks ? 'True' : 'False'));

		$systemConfig->addChild('use_proxy', ($system->useProxy ? 'True' : 'False'));
		$systemConfig->addChild('proxy_login', $system->proxyLogin);
		$systemConfig->addChild('proxy_password', $system->proxyPassword);

		$dedicated->asXML($configDirectory.$filename.'.txt');
	}

	function delete($hostname, $port)
	{
		$this->db()->execute('DELETE FROM Servers WHERE hostname = %s AND port = %d',
				$this->db()->quote($hostname), $port);
	}

	function start($configFile, $matchFile, $isLan)
	{
		$config = \DedicatedManager\Config::getInstance();

		// Starting dedicated
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
				$startCommand = 'START /D "'.$config->dedicatedPath.'" ManiaPlanetServer.exe';
		else $startCommand = 'cd "'.$config->dedicatedPath.'"; ./ManiaPlanetServer';
		$startCommand .= sprintf(' /dedicated_cfg=%s /game_settings=%s',
				escapeshellarg($configFile.'.txt'),
				escapeshellarg('MatchSettings/'.$matchFile.'.txt'));
		if($isLan) $startCommand .= ' /lan';
		if(!$isWindows) $startCommand .= ' &';

		$procHandle = proc_open($startCommand, array(), $pipes, $config->dedicatedPath);
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
				if($isWindows) `TASKKILL /PID $pid`;
				else `kill -9 $pid`;
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
		if(preg_match_all('/ERROR:\s+([^\.$]+)/um', $buffer, $errors))
		{
			if($isWindows) `TASKKILL /PID $pid`;
			else `kill -9 $pid`;

			throw new \Exception(serialize(array_map('ucfirst', $errors[1])));
		}

		// Retrieving XML-RPC port
		if(preg_match('/Listening for xml-rpc commands on port (\d+)/um', $buffer,
						$matches)) $port = $matches[1];
		else throw new \Exception('XML-RPC port not found');

		// Registering server and starting ManiaLive
		$this->startManiaLive($port, $pid);
	}

	function startManiaLive($port, $dedicatedPid = 0)
	{
		$config = \DedicatedManager\Config::getInstance();
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
				$startCommand = 'START php.exe "'.$config->manialivePath.'bootstrapper.php"';
		else $startCommand = 'cd "'.$config->manialivePath.'"; php bootstrapper.php';
		$startCommand .= sprintf(' --rpcport=%d', $port);
		if(!$isWindows) $startCommand .= ' < /dev/null > logs/runtime.'.$dedicatedPid.'.log 2>&1 &';

		sleep(5);

		$procHandle = proc_open($startCommand, array(), $pipes);
		proc_close($procHandle);
	}
	
	protected function toBool($val)
	{
		return (strcasecmp($val, 'true') == 0 || $val == 1);
	}

}

?>