<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 85 $:
 * @author      $Author: martin.gwendal $:
 * @date        $Date: 2012-08-03 17:56:01 +0200 (ven., 03 août 2012) $:
 */

namespace DedicatedManager\Controllers;

use \DedicatedManager\Services\GameInfos;

class CreateServer extends Create
{
	function setConfig()
	{
		parent::setConfig();

		$this->request->redirectArgList('../rules');
	}

	function rules($matchFile = '')
	{
		list(,, $system) = $this->fetchAndAssertConfig(_('setting game options'));

		$service = new \DedicatedManager\Services\MatchSettingsFileService();

		if($matchFile)
		{
			list($gameInfos, $maps, $scriptSettings, $randomize) = $service->get($matchFile);
			$this->session->set('matchFile', $matchFile);
			$this->session->set('gameInfos', $gameInfos);
			$this->session->set('selected', $maps);
			$this->session->set('scriptSettings', $scriptSettings);
			$this->session->set('randomize', $randomize);
		}
		else
		{
			$gameInfos = new GameInfos();
			$scriptSettings = array();
		}

		$scripts = $service->getScriptList($system->title);
		$scriptIds = array();
		$scriptsRules = array();
		foreach($scripts as $script)
		{
			$scriptIds[$script] = uniqid();
			$scriptsRules[$script] = $service->getScriptMatchRules($system->title, $script);
		}
		
		$gameInfos = $this->session->get('gameInfos', $gameInfos);
		if(($gameInfos->scriptName || $system->title) && $scriptSettings)
		{
			foreach($scriptSettings as $scriptSetting)
			{
				$scriptsRules[$gameInfos->scriptName][$scriptSetting->name]->default = $scriptSetting->default;
			}
		}
		$this->response->matchFile = $matchFile;
		$this->response->settingsList = $service->getList();
		$this->response->gameInfos = $gameInfos;
		$this->response->scripts = $scripts;
		$this->response->scriptIds = $scriptIds;
		$this->response->scriptsRules = $scriptsRules;
		$this->response->title = $system->title;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../config');
	}

	function setRules()
	{
		$rules = $this->request->getPostStrict('rules');
		$scriptRules = $this->request->getPost('scriptRules', array());
		list(,, $system) = $this->fetchAndAssertConfig(_('setting game options'));

		$gameInfos = GameInfos::fromArray($rules);
		$gameInfos->chatTime *= isset($rules['chatTime']) ? 1000 : 1;
		$gameInfos->finishTimeout = $gameInfos->finishTimeout < 0 ? 1 :
			(isset($rules['finishTimeout']) ? $gameInfos->finishTimeout * 1000 : $gameInfos->finishTimeout);
		$gameInfos->timeAttackLimit = $gameInfos->timeAttackLimit < 0 ? 1 :
			(isset($rules['timeAttackLimit']) ? $gameInfos->timeAttackLimit * 1000 : $gameInfos->timeAttackLimit);
		$gameInfos->timeAttackSynchStartPeriod *= isset($rules['timeAttackSynchStartPeriod']) ? 1000 : 1;
		$gameInfos->lapsTimeLimit = $gameInfos->lapsTimeLimit < 0 ? 1 :
			(isset($rules['lapsTimeLimit']) ? $gameInfos->lapsTimeLimit * 1000 : $gameInfos->lapsTimeLimit);

		$defaultRules = array();
		if($gameInfos->gameMode == GameInfos::GAMEMODE_SCRIPT && $gameInfos->scriptName)
		{
			$service = new \DedicatedManager\Services\MatchSettingsFileService();
			$defaultRules = $service->getScriptMatchRules($system->title, $gameInfos->scriptName);
			
			if(isset($scriptRules[$gameInfos->scriptName]))
				foreach($scriptRules[$gameInfos->scriptName] as $name => $value)
				{
					$defaultRules[$name]->default = $value;
				}
		}
		
		$this->session->set('gameInfos', $gameInfos);
		$this->session->set('scriptSettings', $defaultRules);

		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		if(($errors = $service->validate($gameInfos)))
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../rules');
		}

		$this->request->redirectArgList('../maps');
	}

	function maps()
	{
		set_time_limit(0);
		list(,, $system) = $this->fetchAndAssertConfig(_('selecting maps'));
		$gameInfos = $this->fetchAndAssertSettings(_('selecting maps'));

		$service = new \DedicatedManager\Services\TitleService();
		$environment = $service->getEnvironment($system->title);

		if($gameInfos->gameMode == GameInfos::GAMEMODE_SCRIPT)
		{
			$service = new \DedicatedManager\Services\MatchSettingsFileService();
			$type = $service->getScriptMapType($gameInfos->scriptName, $system->title);
		}
		else
		{
			$type = array('Race');
		}

		$isLaps = $gameInfos->gameMode == GameInfos::GAMEMODE_LAPS;

		$service = new \DedicatedManager\Services\MapService();
		$this->response->files = $service->getList('', true, $isLaps, $type, $environment);
		$this->response->selected = $this->session->get('selected', array());
		$this->response->randomize = $this->session->get('randomize', false);

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to game settings');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../rules');
	}

	function setMaps()
	{
		$selected = $this->request->getPost('selected','');
		$randomize = $this->request->getPost('randomize',0);
		$this->fetchAndAssertConfig(_('selecting maps'));
		$this->fetchAndAssertSettings(_('selecting maps'));

		$this->session->set('selected', explode('|', $selected));
		$this->session->set('randomize', $randomize);

		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../maps/');
		}

		$this->request->redirectArgList('../preview');
	}

	function preview()
	{
		list($options) = $this->fetchAndAssertConfig(_('starting it'));
		$this->fetchAndAssertSettings(_('starting server'));
		$this->fetchAndAssertMaps(_('starting server'));

		$defaultFileName = \ManiaLib\Utils\Formatting::stripStyles($options->name);
		$this->response->configFile = $this->session->get('configFile', $defaultFileName);
		$this->response->matchFile = $this->session->get('matchFile', $defaultFileName);

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to map selection');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../maps');
	}

	function start()
	{
		$configFile = $this->request->getPostStrict('configFile');
		$matchFile = $this->request->getPostStrict('matchFile');
		list($options, $account, $system, $authLevel, $isLan) = $this->fetchAndAssertConfig(_('starting it'));
		$gameInfos = $this->fetchAndAssertSettings(_('starting server'));
		$maps = $this->fetchAndAssertMaps(_('starting server'));
		$scriptRules = $this->session->get('scriptSettings', array());
		$randomize = $this->session->get('randomize', false);
		$this->session->set('configFile', $configFile);
		$this->session->set('matchFile', $matchFile);

		$errors = array();
		if(strpbrk($configFile, '\\/:*?"<>|'))
		{
			$errors[] = _('The server config filename must not contain any of the following characters: \\ / : * ? " < > |');
		}
		if(strpbrk($matchFile, '\\/:*?"<>|'))
		{
			$errors[] = _('The match settings filename must not contain any of the following characters: \\ / : * ? " < > |');
		}

		if(!$errors)
		{
			try
			{
				$error = _('An error appeared while writing the server configuration file.');
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->save($configFile, $options, $account, $system, $authLevel);

				$error = _('An error appeared while writing the MatchSettings file.');
				$service = new \DedicatedManager\Services\MatchSettingsFileService();
				$service->save($matchFile, $gameInfos, $maps, $scriptRules, $randomize);

				$error = _('An error appeared while starting the server.');
				$service = new \DedicatedManager\Services\ServerService();
				$server = new \DedicatedManager\Services\Server();
				$server->rpcHost = '127.0.0.1';
				$server->rpcPort = $service->start($configFile, $matchFile, $isLan);
				$server->rpcPassword = $authLevel->superAdmin;
				$service->register($server);
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$errors[] = $error;
			}
		}

		if($errors)
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../preview');
		}

		$this->session->set('success', _('Your server has been successfully started'));
		$this->goHome();
	}
	
	function quickStart($configFile = '', $matchFile = '', $isLan = false, $serverName = null, $login = null, $password = null, $title = null)
	{
		$service = new \DedicatedManager\Services\ConfigFileService();
		$configFileList = $service->getList();
		if($configFile)
		{
			list($config, $account, $system,) = $service->get($configFile);
		}
		else
		{
			$config = new \DedicatedManager\Services\ServerOptions();
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
		}
		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		$matchSettingsFileList = $service->getList();
		$service = new \DedicatedManager\Services\TitleService();
		$titles = $service->getList();
		
		$this->response->configFileList = $configFileList;
		$this->response->matchSettingsFileList = $matchSettingsFileList;
		$this->response->titles = $titles;
		$this->response->configFile = $configFile;
		$this->response->matchFile = $matchFile;
		$this->response->isLan = $isLan;
		$this->response->serverName = ($config->name && !$serverName ? $config->name : $serverName);
		$this->response->serverLogin = ($account->login && !$login ? $account->login : $login);
		$this->response->serverPassword = ($account->password && !$password ? $account->password : $password);
		$this->response->title = ($system->title && !$title ? $system->title : $title);

	}
	
	function doQuickStart()
	{
		$configFile = $this->request->getPostStrict('configFile');
		$matchFile = $this->request->getPostStrict('matchFile');
		$serverName = $this->request->getPost('serverName', '');
		$login = $this->request->getPost('login', '');
		$password = $this->request->getPost('password', '');
		$title = $this->request->getPost('title', null);
		$isLan = $this->request->getPost('isLan', false);
		
		$options = array();
		if($serverName)
		{
			$options['servername'] = $serverName;
		}
		if($login)
		{
			$options['login'] = $login;
		}
		if($password)
		{
			$options['password'] = $password;
		}
		if($title)
		{
			$options['title'] = $title;
		}
		
		$errors = array();
		try
		{
			$configService = new \DedicatedManager\Services\ConfigFileService();
			list(,,, $authLevel) = $configService->get($configFile);

			$error = _('An error appeared while starting the server.');
			$service = new \DedicatedManager\Services\ServerService();
			$server = new \DedicatedManager\Services\Server();
			$server->rpcHost = '127.0.0.1';
			$server->rpcPort = $service->start($configFile, $matchFile, (bool) $isLan, $options);
			$server->rpcPassword = $authLevel->superAdmin;
			$service->register($server);
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$errors[] = $error;
		}
		
		if($errors)
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../quickStart');
		}

		$this->session->set('success', _('Your server has been successfully started'));
		$this->goHome();
	}

	function goHome()
	{
		$this->session->delete('gameInfos');
		$this->session->delete('selected');
		$this->session->delete('configFile');
		$this->session->delete('matchFile');
		parent::goHome();
	}

	protected function fetchAndAssertSettings($actionStr)
	{
		try
		{
			$gameInfos = $this->session->getStrict('gameInfos');
			return $gameInfos;
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to set game options before %s.'), $actionStr));
			$this->request->redirectArgList('../rules');
		}
	}

	protected function fetchAndAssertMaps($actionStr)
	{
		try
		{
			$maps = $this->session->getStrict('selected');
			$this->session->getStrict('randomize');
			return $maps;
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to select maps before %s.'), $actionStr));
			$this->request->redirectArgList('../maps');
		}
	}
}

?>