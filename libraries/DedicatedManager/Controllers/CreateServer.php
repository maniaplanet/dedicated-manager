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
	function setConfig(array $options, array $account, array $system, array $authLevel, $isOnline = 0)
	{
		parent::setConfig($options, $account, $system, $authLevel, $isOnline);

		$this->request->redirectArgList('../rules');
	}

	function rules($matchFile = '')
	{
		list(,, $system) = $this->fetchAndAssertConfig(_('setting game options'));

		$service = new \DedicatedManager\Services\MatchSettingsFileService();

		if($matchFile)
		{
			list($gameInfos, $maps, $scriptSettings) = $service->get($matchFile);
			$this->session->set('matchFile', $matchFile);
			$this->session->set('gameInfos', $gameInfos);
			$this->session->set('selected', $maps);
			$this->session->set('scriptSettings', $scriptSettings);
			$this->session->set('scriptSettings', $scriptSettings);
		}
		else
		{
			$gameInfos = new GameInfos();
			$scriptSettings = array();
		}

		$scripts = $service->getScriptList($system->title);
		$rules = array();
		foreach($scripts as $script)
		{
			$rules[$script] = $service->getScriptMatchRules($system->title, $script);
		}
		
		$gameInfos = $this->session->get('gameInfos', $gameInfos);
		if(($gameInfos->scriptName || $system->title) && $scriptSettings)
		{
			foreach($scriptSettings as $scriptSetting)
			{
				$rules[$gameInfos->scriptName][$scriptSetting->name]->default = $scriptSetting->default;
			}
		}
		$this->response->matchFile = $matchFile;
		$this->response->settingsList = $service->getList();
		$this->response->gameInfos = $gameInfos;
		$this->response->scripts = $scripts;
		$this->response->scriptsRules = $rules;
		$this->response->title = $system->title;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../config');
	}

	function setRules($rules, array $scriptRules = array())
	{
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

		if($gameInfos->scriptName)
		{
			$service = new \DedicatedManager\Services\MatchSettingsFileService();
			$rules = $service->getScriptMatchRules($system->title, $gameInfos->scriptName);
			
			foreach($scriptRules[$gameInfos->scriptName] as $name => $value)
			{
				$rules[$name]->default = $value;
			}
		}
		
		$this->session->set('gameInfos', $gameInfos);
		$this->session->set('scriptSettings', $rules);

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

		//TODO Find a way to clean this mess
		$environment = in_array($system->title, array('TMCanyon', 'Platform@nadeolive')) ? 'Canyon' : 'Storm';

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

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to game settings');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../rules');
	}

	function setMaps($selected = '')
	{
		$this->fetchAndAssertConfig(_('selecting maps'));
		$this->fetchAndAssertSettings(_('selecting maps'));

		$this->session->set('selected', explode('|', $selected));

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

	function start($configFile, $matchFile)
	{
		list($options, $account, $system, $authLevel, $isLan) = $this->fetchAndAssertConfig(_('starting it'));
		$gameInfos = $this->fetchAndAssertSettings(_('starting server'));
		$maps = $this->fetchAndAssertMaps(_('starting server'));
		$scriptRules = $this->session->get('scriptSettings', array());
		
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
				$service->save($matchFile, $gameInfos, $maps, $scriptRules);

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