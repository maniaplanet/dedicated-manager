<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

use \DedicatedManager\Services\GameInfos;

class Create extends \ManiaLib\Application\Controller
{
	protected $defaultAction = 'configure';
	
	protected function onConstruct()
	{
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftLink = $this->request->createLinkArgList('../go-home');
	}

	function configure($configFile = '')
	{
		$service = new \DedicatedManager\Services\ServerService();
		$configList = $service->getConfigFileList();

		if($configFile)
		{
			list($config, $account, $system) = $service->getConfig($configFile);
		}
		else
		{
			$config = new \ManiaLive\DedicatedApi\Structures\ServerOptions();
			$config->nextMaxPlayers = 16;
			$config->nextMaxSpectators = 16;
			$config->password = '';
			$config->passwordForSpectator = '';
			$config->allowMapDownload = true;
			$config->callVoteRatio = 0.5;
			$config->nextCallVoteTimeOut = 60000;
			$config->refereePassword = '';
			$config->refereeMode = 0;
			$config->nextLadderMode = true;
			$config->ladderServerLimitMin = 0;
			$config->ladderServerLimitMax = 50000;
			$config->autoSaveReplays = false;
			$config->autoSaveValidationReplays = false;
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
		}
		$account = $this->session->get('account', $account);
		$config = $this->session->get('serverOptions', $config);
		$system = $this->session->get('systemConfig', $system);
		$this->session->set('configFile', $configFile);
		$this->response->configList = $configList;
		$this->response->serverOptions = $config;
		$this->response->account = $account;
		$this->response->system = $system;
	}

	function saveServerConfig(array $config, array $account, array $system, $isOnline = 0)
	{
		$errors = array();
		if(!$system['title'])
		{
			$errors[] = _('You have to select a game title');
		}
		if($config['name'] == '')
		{
			$errors[] = _('You have to fill the "Name" field.');
		}
		if($config['nextMaxPlayers'] <= 0)
		{
			$errors[] = _('You have to set a positive value for the "Max players" field.');
		}
		if($config['nextMaxSpectators'] < 0)
		{
			$errors[] = _('You have to set a positive value for the "Max spectators" field.');
		}
		if($config['nextMaxSpectators'] + $config['nextMaxPlayers'] > 250)
		{
			$errors[] = _('Too many player allowed. Total must be lower than 250.');
		}
		if(($config['callVoteRatio'] != -1 && $config['callVoteRatio'] < 0) || $config['callVoteRatio'] > 100)
		{
			$errors[] = _('The vote ratio has to be between 0 and 100, it can take the value -1 if vote are disabled.');
		}
		if($account['login'] && !preg_match('/^[a-z0-9_\-.]{1,25}$/ixu', $account['login']))
		{
			$errors[] = _('The login entered is invalid, please check it.');
		}
		if($account['password'] && strlen($account['password']) > 20)
		{
			$errors[] = _('The password entered is invalid, please check it.');
		}

		if(!empty($errors))
		{
			$this->session->set('error', $errors);
			$this->session->delete('configFile');
			$this->request->redirectArgList('../configure');
		}
		
		$tmp = new \DedicatedManager\Services\Account();
		foreach($account as $key => $value)
		{
			$tmp->$key = $value;
		}
		
		$systemConfig = new \DedicatedManager\Services\SystemConfig();
		foreach($system as $key => $value)
		{
			$systemConfig->$key = $value;
		}
		$account = $tmp;
		$serverOptions = \DedicatedManager\Services\ServerOptions::fromArray($config);
		$serverOptions->callVoteRatio = $serverOptions->callVoteRatio < 0 ? $serverOptions->callVoteRatio : $serverOptions->callVoteRatio / 100;
		$serverOptions->nextCallVoteTimeOut = $serverOptions->nextCallVoteTimeOut * 1000;
		$this->session->set('account', $account);
		$this->session->set('serverOptions', $serverOptions);
		$this->session->set('systemConfig', $systemConfig);
		$this->session->set('isLan', !$isOnline);

		$this->request->redirectArgList('../match-settings');
	}

	function matchSettings($matchFile = '')
	{
		$this->session->getStrict('serverOptions');
		$system = $this->session->getStrict('systemConfig');
		$this->session->getStrict('account');
		$service = new \DedicatedManager\Services\MatchService();
		$matchSettingsFiles = $service->getMatchSettingsFilesList();

		$maps = array();

		if($matchFile)
		{
			list($matchSettings, $maps) = $service->get($matchFile);
		}
		else
		{
			$matchSettings = new GameInfos();
		}

		$matchSettings = $this->session->get('matchSettings', $matchSettings);
		$this->session->delete('matchSettings', $matchSettings);

		$scripts = $service->getScriptList($system->title);

		$this->session->set('matchFile', $matchFile);
		$this->response->files = $matchSettingsFiles;
		$this->response->matchSettings = $matchSettings;
		$this->response->maps = $maps;
		$this->response->scripts = $scripts;
		$this->response->title = $system->title;
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('..');
	}

	function saveMatchSettings($matchSettings, $maps = array())
	{
		$this->session->getStrict('serverOptions');
		$this->session->getStrict('systemConfig');
		$this->session->getStrict('account');
		$matchSettingsObj = GameInfos::fromArray($matchSettings);
		$matchSettingsObj->chatTime *= (array_key_exists('chatTime', $matchSettings) ? 1000 : 1);
		$this->session->set('matchSettings', $matchSettingsObj);

		$selected = array_map(function ($m)
				{
					return str_replace('\\', '/', $m);
				}, $maps);
		$this->request->set('selected', $selected);

		if($matchSettingsObj->gameMode === null)
		{
			$this->session->set('error', _('You have to select a game mode'));
		}
		else if($matchSettingsObj->gameMode == \ManiaLive\DedicatedApi\Structures\GameInfos::GAMEMODE_SCRIPT && $matchSettingsObj->scriptName == '')
		{
			$this->session->set('error', _('You have to select a script to play in script mode'));
		}
		
		if($this->session->get('error'))
		{
			$this->request->set('matchFile', $this->session->get('matchFile'));
			$this->request->redirectArgList('../match-settings', 'matchFile');
		}
		$this->request->redirectArgList('../select-maps', 'selected');
	}

	function selectMaps($selected = array())
	{
		set_time_limit(0);
		$this->session->getStrict('serverOptions');
		$system = $this->session->getStrict('systemConfig');
		$this->session->getStrict('account');
		$matchSettings = $this->session->getStrict('matchSettings');
		$matchService = new \DedicatedManager\Services\MatchService();

		//TODO Find a way to clean this mess
		if($system->title == 'TMCanyon')
		{
			$environment = 'Canyon';
		}
		else
		{
			$environment = 'Storm';
		}

		if($matchSettings->gameMode == GameInfos::GAMEMODE_SCRIPT)
		{
			$script = $matchSettings->scriptName;
			$type = 'script';
			$type = $matchService->getScriptMapType($script, $system->title);
		}
		else
		{
			$type = array('Race');
		}

		if($matchSettings->gameMode == GameInfos::GAMEMODE_LAPS)
		{
			$isLaps = true;
		}
		else
		{
			$isLaps = false;
		}
		$service = new \DedicatedManager\Services\FileService();
		$files = array();
		$files = $service->getList('', true, $isLaps, $type, $environment);

		$selected = $this->session->get('selected', $selected);
		$this->session->delete('selected', $selected);

		$this->response->files = $files;
		$this->response->selected = $selected;
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to match configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../match-settings');
	}

	function saveFiles($selected = '')
	{
		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../select-maps/');
		}

		$serverOptions = $this->session->getStrict('serverOptions');
		$system = $this->session->getStrict('systemConfig');
		$this->session->getStrict('account');
		$this->session->getStrict('matchSettings');

		$this->session->set('selected', explode(',', $selected));

		if(!$this->session->get('configFile'))
		{
			$configFile = $serverOptions->name;
		}
		else
		{
			$configFile = $this->session->get('configFile');
		}

		if(!$this->session->get('matchFile'))
		{
			$matchFile = $serverOptions->name;
		}
		else
		{
			$matchFile = $this->session->get('matchFile');
		}

		$this->response->configFile = \ManiaLib\Utils\Formatting::stripStyles($configFile);
		$this->response->matchFile = \ManiaLib\Utils\Formatting::stripStyles($matchFile);
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to map selection');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../select-maps');
	}

	function startServer($configFile, $matchFile)
	{
		$count = 0;
		str_ireplace(array('/', '\\', ':', '*', '!', '<', '>', '|'), '', $configFile, $count);
		if($count)
		{
			$this->session->set('error',
					_('The filename must not contain any of the following characters: "/","\\",":","*","!","<",">","|"'));
			$this->session->set('configFile', $configFile);
			$this->session->set('matchFile', $matchFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-files/', 'selected');
		}

		str_ireplace(array('/', '\\', ':', '*', '!', '<', '>', '|'), '', $matchFile, $count);
		if($count)
		{
			$this->session->set('error',
					_('The filename must not contain any of the following characters: "/","\\",":","*","!","<",">","|"'));
			$this->session->set('configFile', $configFile);
			$this->session->set('matchFile', $matchFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-files/', 'selected');
		}

		$serverOptions = $this->session->getStrict('serverOptions');
		$account = $this->session->getStrict('account');
		$system = $this->session->getStrict('systemConfig');
		$matchSettings = $this->session->getStrict('matchSettings');
		$maps = $this->session->getStrict('selected');
		$isLan = $this->session->get('isLan');
		try
		{
			$service = new \DedicatedManager\Services\MatchService();
			$service->save($matchFile, $matchSettings, $maps);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error appeared while writing the MatchSettings file'));
			$this->session->set('configFile', $configFile);
			$this->session->set('matchFile', $matchFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-files/', 'selected');
		}

		try
		{
			$service = new \DedicatedManager\Services\ServerService();
			$service->save($configFile, $serverOptions, $account, $system);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error appeared while writing the server configuration file'));
			$this->session->set('configFile', $configFile);
			$this->session->set('matchFile', $matchFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-files/', 'selected');
		}

		try
		{
			$service->start($configFile, $matchFile, $isLan);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error appeared while starting the server and ManiaLive'));
			$this->session->set('configFile', $configFile);
			$this->session->set('matchFile', $matchFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-files/', 'selected');
		}

		$this->session->delete('serverOptions');
		$this->session->delete('account');
		$this->session->delete('matchSettings');
		$this->session->delete('selected');

		$this->session->set('success', _('Your server has been started successfully'));
		$this->request->redirectArgList('/');
	}

	function relay($configFile = '')
	{
		$service = new \DedicatedManager\Services\ServerService();
		$configList = $service->getConfigFileList();

		if($configFile)
		{
			list($config, $account, $system) = $service->getConfig($configFile);
		}
		else
		{
			$config = new \ManiaLive\DedicatedApi\Structures\ServerOptions();
			$config->nextMaxPlayers = 0;
			$config->nextMaxSpectators = 16;
			$config->passwordForSpectator = '';
			$config->allowMapDownload = true;
			$config->refereePassword = '';
			$config->refereeMode = 0;
			$config->autoSaveReplays = false;
			$config->autoSaveValidationReplays = false;
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
		}
		$account = $this->session->get('account', $account);
		$config = $this->session->get('serverOptions', $config);
		$system = $this->session->get('systemConfig', $system);
		$this->session->set('configFile', $configFile);
		$this->response->configList = $configList;
		$this->response->serverOptions = $config;
		$this->response->account = $account;
		$this->response->system = $system;
		$this->response->configFile = $configFile;
	}
	
	function saveRelayConfig(array $config, array $account, array $system, $isOnline = 0)
	{
		$errors = array();
		if(!$system['title'])
		{
			$errors[] = _('You have to select a game title');
		}
		if($config['name'] == '')
		{
			$errors[] = _('You have to fill the "Name" field.');
		}
		if($config['nextMaxSpectators'] < 0)
		{
			$errors[] = _('You have to set a positive value for the "Max spectators" field.');
		}
		if($config['nextMaxSpectators'] + $config['nextMaxPlayers'] > 250)
		{
			$errors[] = _('Too many player allowed. Total must be lower than 250.');
		}
		if($account['login'] && !preg_match('/^[a-z0-9_\-.]{1,25}$/ixu', $account['login']))
		{
			$errors[] = _('The login entered is invalid, please check it.');
		}
		if($account['password'] && strlen($account['password']) > 20)
		{
			$errors[] = _('The password entered is invalid, please check it.');
		}

		if(!empty($errors))
		{
			$this->session->set('error', $errors);
			$this->session->delete('configFile');
			$this->request->redirectArgList('../configure');
		}
		
		$tmp = new \DedicatedManager\Services\Account();
		foreach($account as $key => $value)
		{
			$tmp->$key = $value;
		}
		
		$systemConfig = new \DedicatedManager\Services\SystemConfig();
		foreach($system as $key => $value)
		{
			$systemConfig->$key = $value;
		}
		$account = $tmp;
		$serverOptions = \DedicatedManager\Services\ServerOptions::fromArray($config);
		$this->session->set('account', $account);
		$this->session->set('serverOptions', $serverOptions);
		$this->session->set('systemConfig', $systemConfig);
		$this->session->set('isLan', !$isOnline);

		$this->request->redirectArgList('../save-file');
	}
	
	function saveFile()
	{
		$serverOptions = $this->session->getStrict('serverOptions');
		$this->session->getStrict('systemConfig');
		$this->session->getStrict('account');

		if(!$this->session->get('configFile'))
		{
			$configFile = $serverOptions->name;
		}
		else
		{
			$configFile = $this->session->get('configFile');
		}

		$this->response->configFile = \ManiaLib\Utils\Formatting::stripStyles($configFile);
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to relay server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../relay');
	}

	function startRelay($configFile)
	{
		$count = 0;
		str_ireplace(array('/', '\\', ':', '*', '!', '<', '>', '|'), '', $configFile, $count);
		if($count)
		{
			$this->session->set('error', _('The filename must not contain any of the following characters: "/","\\",":","*","!","<",">","|"'));
			$this->session->set('configFile', $configFile);
			$this->request->set('selected', implode(',', $this->session->getStrict('selected')));
			$this->request->redirectArgList('../save-file/');
		}

		$serverOptions = $this->session->getStrict('serverOptions');
		$account = $this->session->getStrict('account');
		$system = $this->session->getStrict('systemConfig');
		$isLan = $this->session->get('isLan');

		try
		{
			$service = new \DedicatedManager\Services\ServerService();
			$service->save($configFile, $serverOptions, $account, $system);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error appeared while writing the server configuration file'));
			$this->session->set('configFile', $configFile);
			$this->request->redirectArgList('../save-file/');
		}

		try
		{
			$service->startRelay($configFile, $isLan);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error appeared while starting the server and ManiaLive'));
			$this->session->set('configFile', $configFile);
			$this->request->redirectArgList('../save-file/');
		}

		$this->session->delete('serverOptions');
		$this->session->delete('account');
		$this->session->delete('matchSettings');
		$this->session->delete('selected');

		$this->session->set('success', _('Your relay server has been started successfully'));
		$this->request->redirectArgList('/');
	}

	function goHome()
	{
		$this->session->delete('serverOptions');
		$this->session->delete('systemConfig');
		$this->session->delete('account');
		$this->session->delete('matchSettings');
		$this->session->delete('selected');
		$this->session->delete('configFile');
		$this->session->delete('matchFile');
		$this->session->delete('isLan');
		$this->request->redirectArgList('/');
	}
}

?>