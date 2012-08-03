<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class ConfigFileService extends DedicatedFileService
{

	function __construct()
	{
		$this->directory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Config/';
		$this->rootTag = '<dedicated>';
	}

	function validate(ServerOptions $options, Account $account = null, SystemConfig $system = null, $isLan = true, AuthorizationLevels $auth = null)
	{
		$errors = array();
		if($system && !$system->title)
		{
			$errors[] = _('You have to select a game title');
		}
		if(!$options->name)
		{
			$errors[] = _('You have to fill the "Name" field.');
		}
		if($options->nextMaxPlayers <= 0)
		{
			$errors[] = _('You have to set a positive value for the "Max players" field.');
		}
		if($options->nextMaxSpectators < 0)
		{
			$errors[] = _('You have to set a positive value for the "Max spectators" field.');
		}
		if($options->nextMaxSpectators + $options->nextMaxPlayers > 255)
		{
			$errors[] = _('Too many player allowed. Total must be lower than 255.');
		}
		if(($options->callVoteRatio != -1 && $options->callVoteRatio < 0) || $options->callVoteRatio > 1)
		{
			$errors[] = _('The vote ratio has to be between 0 and 100, it can take the value -1 to disable votes.');
		}
		if(!$isLan && $account)
		{
			if(!preg_match('/^[a-z0-9_.-]{1,25}$/ixu', $account->login))
			{
				$errors[] = _('The login entered is invalid, please check it.');
			}
			if(!$account->password || strlen($account->password) > 20)
			{
				$errors[] = _('The password entered is invalid, please check it.');
			}
		}
		if(!$auth->superAdminPassword)
		{
			$errors[] = _('SuperAdmin password can\'t be empty');
		}
		if(!$auth->adminPassword)
		{
			$errors[] = _('Admin password can\'t be empty');
		}
		if(!$auth->userPassword)
		{
			$errors[] = _('User password can\'t be empty');
		}

		return $errors;
	}

	function get($filename)
	{
		if(!file_exists($this->directory.$filename.'.txt'))
		{
			throw new \InvalidArgumentException('File does not exists');
		}

		$configObj = simplexml_load_file($this->directory.$filename.'.txt');

		$authLevel = new AuthorizationLevels();
		foreach($configObj->authorization_levels->level as $level)
		{
			switch($level->name)
			{
				case 'SuperAdmin':$authLevel->superAdminPassword = (string) $level->password;
					break;
				case 'Admin':$authLevel->adminPassword = (string) $level->password;
					break;
				case 'User':$authLevel->userPassword = (string) $level->password;
					break;
			}
		}
		
		$config = new ServerOptions();
		$config->name = (string) $configObj->server_options->name;
		$config->comment = (string) $configObj->server_options->comment;
		$config->password = (string) $configObj->server_options->password;
		$config->passwordForSpectator = (string) $configObj->server_options->password_spectator;
		$config->nextMaxPlayers = (int) $configObj->server_options->max_players;
		$config->nextMaxSpectators = (int) $configObj->server_options->max_spectators;
		$config->hideServer = (int) $configObj->server_options->hide_server;
		$config->isP2PUpload = self::toBool($configObj->server_options->enable_p2p_upload);
		$config->isP2PDownload = self::toBool($configObj->server_options->enable_p2p_download);
		$config->nextLadderMode = (string) $configObj->server_options->ladder_mode;
		$config->nextLadderMode = (int) ($config->nextLadderMode == 1 || $config->nextLadderMode == 'forced');
		$config->ladderServerLimitMax = (int) $configObj->server_options->ladder_serverlimit_max;
		$config->ladderServerLimitMin = (int) $configObj->server_options->ladder_serverlimit_min;
		$config->nextCallVoteTimeOut = (int) $configObj->server_options->callvote_timeout;
		$config->callVoteRatio = (float) $configObj->server_options->callvote_ratio;
		$config->allowMapDownload = self::toBool($configObj->server_options->allow_map_download);
		$config->autoSaveReplays = self::toBool($configObj->server_options->autosave_replays);
		$config->autoSaveValidationReplays = self::toBool($configObj->server_options->autosave_validation_replays);
		$config->refereePassword = (string) $configObj->server_options->referee_password;
		$config->refereeMode = (string) $configObj->server_options->referee_validation_mode;
		$config->nextUseChangingValidationSeed = self::toBool($configObj->server_options->use_changing_validation_seed);

		$account = new Account();
		$account->login = (string) $configObj->masterserver_account->login;
		$account->password = (string) $configObj->masterserver_account->password;
		$account->validationKey = (string) $configObj->masterserver_account->validation_key;

		$system = new SystemConfig();
		$system->connectionUploadrate = (int) $configObj->system_config->connection_uploadrate;
		$system->connectionDownloadrate = (int) $configObj->system_config->connection->downnloadrate;
		$system->allowSpectatorRelays = self::toBool($configObj->system_config->allow_spectator_relays);
		$system->p2pCacheSize = (int) $configObj->system_config->p2p_cache_size;
		$system->forceIpAddress = (string) $configObj->system_config->force_ip_address;
		$system->serverPort = (int) $configObj->system_config->server_port;
		$system->serverP2pPort = (int) $configObj->system_config->server_p2p_port;
		$system->clientPort = (int) $configObj->system_config->clientPort;
		$system->bindIpAddress = (string) $configObj->system_config->bind_ip_address;
		$system->useNatUpnp = self::toBool($configObj->system_config->use_nat_upnp);
		$system->xmlrpcPort = (int) $configObj->system_config->xmlrpc_port;
		$system->xmlrpcAllowremote = (string) $configObj->system_config->xmlrpc_allowremote;
		$system->blacklistUrl = (string) $configObj->system_config->blacklist_url;
		$system->guestlistFilename = (string) $configObj->system_config->guestlist_filename;
		$system->blacklistFilename = (string) $configObj->system_config->blacklist_filename;
		$system->title = (string) $configObj->system_config->title;
		$system->minimumClientBuild = (string) $configObj->system_config->minimum_client_build;
		$system->disableCoherenceChecks = self::toBool($configObj->system_config->disable_coherence_checks);
		$system->useProxy = self::toBool($configObj->system_config->use_proxy);
		$system->proxyLogin = (string) $configObj->system_config->proxy_login;
		$system->proxyPassword = (string) $configObj->system_config->proxyPassword;

		return array($config, $account, $system, $authLevel);
	}

	function save($filename, ServerOptions $config, Account $account = null, SystemConfig $system = null, AuthorizationLevels $auth = null)
	{
		$dom = new \DOMDocument('1.0', 'utf-8');
		$dedicated = simplexml_import_dom($dom->createElement('dedicated'));

		
		if(!$auth)
		{
			$auth = new AuthorizationLevels();
		}
		$authLevel = $dedicated->addChild('authorization_levels');
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'SuperAdmin');
		$level->addChild('password', $auth->superAdminPassword);
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'Admin');
		$level->addChild('password', $auth->adminPassword);
		$level = $authLevel->addChild('level');
		$level->addChild('name', 'User');
		$level->addChild('password', $auth->userPassword);

		if(!$account)
		{
			$account = new Account();
		}
		$masterAccount = $dedicated->addChild('masterserver_account');
		$masterAccount->addChild('login', (string) $account->login);
		$masterAccount->addChild('password', (string) $account->password);
		$masterAccount->addChild('validation_key', (string) $account->validationKey);

		$serverOptions = $dedicated->addChild('server_options');
		$serverOptions->addChild('name', (string) $config->name);
		$serverOptions->addChild('comment', (string) $config->comment);
		$serverOptions->addChild('hide_server', (int) $config->hideServer);
		$serverOptions->addChild('max_players', (int) $config->nextMaxPlayers);
		$serverOptions->addChild('password', (string) $config->password);
		$serverOptions->addChild('max_spectators', (int) $config->nextMaxSpectators);
		$serverOptions->addChild('password_spectator', (string) $config->passwordForSpectator);
		$serverOptions->addChild('ladder_mode', (int) $config->nextLadderMode);
		$serverOptions->addChild('ladder_serverlimit_min', (int) $config->ladderServerLimitMin);
		$serverOptions->addChild('ladder_serverlimit_max', (int) $config->ladderServerLimitMax);
		$serverOptions->addChild('enable_p2p_upload', $config->isP2PUpload ? 'True' : 'False');
		$serverOptions->addChild('enable_p2p_download', $config->isP2PDownload ? 'True' : 'False');
		$serverOptions->addChild('callvote_timeout', (int) $config->nextCallVoteTimeOut);
		$serverOptions->addChild('callvote_ratio', (float) $config->callVoteRatio);
		$serverOptions->addChild('allow_map_download', $config->allowMapDownload ? 'True' : 'False');
		$serverOptions->addChild('autosave_replays', $config->autoSaveReplays ? 'True' : 'False');
		$serverOptions->addChild('autosave_validation_replays', $config->autoSaveValidationReplays ? 'True' : 'False');
		$serverOptions->addChild('referee_password', (string) $config->refereePassword);
		$serverOptions->addChild('referee_validation_mode', (string) $config->refereeMode);
		$serverOptions->addChild('use_changing_validation_seed', (string) $config->nextUseChangingValidationSeed);

		//TODO find a wat to change system settings if needed
		if(!$system)
		{
			$system = new SystemConfig();
		}
		// TODO add casts
		$systemConfig = $dedicated->addChild('system_config');
		$systemConfig->addChild('connection_uploadrate', $system->connectionUploadrate);
		$systemConfig->addChild('connection_downloadrate', $system->connectionDownloadrate);
		$systemConfig->addChild('allow_spectator_relays', $system->allowSpectatorRelays ? 'True' : 'False');
		$systemConfig->addChild('p2p_cache_size', $system->p2pCacheSize);
		$systemConfig->addChild('force_ip_address', $system->forceIpAddress);
		$systemConfig->addChild('server_port', $system->serverPort);
		$systemConfig->addChild('server_p2p_port', $system->serverP2pPort);
		$systemConfig->addChild('client_port', $system->clientPort);
		$systemConfig->addChild('bind_ip_address', $system->bindIpAddress);
		$systemConfig->addChild('use_nat_upnp', $system->useNatUpnp ? 'True' : 'False');
		$systemConfig->addChild('xmlrpc_port', $system->xmlrpcPort);
		$systemConfig->addChild('xmlrpc_allowremote', $system->xmlrpcAllowremote);
		$systemConfig->addChild('blacklist_url', $system->blacklistUrl);
		$systemConfig->addChild('guestlist_filename', $system->guestlistFilename);
		$systemConfig->addChild('blacklist_filename', $system->blacklistFilename);
		$systemConfig->addChild('title', $system->title);
		$systemConfig->addChild('minimum_client_build', $system->minimumClientBuild);
		$systemConfig->addChild('disable_coherence_checks', $system->disableCoherenceChecks ? 'True' : 'False');
		$systemConfig->addChild('use_proxy', $system->useProxy ? 'True' : 'False');
		$systemConfig->addChild('proxy_login', $system->proxyLogin);
		$systemConfig->addChild('proxy_password', $system->proxyPassword);

		$dedicated->asXML($this->directory.$filename.'.txt');
	}

}

?>
