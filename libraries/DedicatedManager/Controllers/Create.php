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

class Create extends AbstractController
{
	protected $defaultAction = 'configure';

	protected function onConstruct()
	{
		parent::onConstruct();
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftLink = $this->request->createLinkArgList('../go-home');
	}
	
	function preFilter()
	{
		parent::preFilter();
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to create a server.'));
			$this->request->redirectArgList('/');
		}
	}

	function configure($configFile = '')
	{
		$service = new \DedicatedManager\Services\ConfigFileService();

		if($configFile)
		{
			list($options, $account, $system) = $service->get($configFile);
			$this->session->set('configFile', $configFile);
			$this->session->set('options', $options);
			$this->session->set('account', $account);
			$this->session->set('system', $system);
		}
		else
		{
			$options = new \DedicatedManager\Services\ServerOptions();
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
		}
		
		$this->response->configList = $service->getList();
		$this->response->options = $this->session->get('options', $options);
		$this->response->account = $this->session->get('account', $account);
		$this->response->system = $this->session->get('system', $system);
	}

	function saveServerConfig(array $options, array $account, array $system, $isOnline = 0)
	{
		$options = \DedicatedManager\Services\ServerOptions::fromArray($options);
		$options->callVoteRatio = $options->callVoteRatio < 0 ? -1 : $options->callVoteRatio / 100;
		$options->nextCallVoteTimeOut = $options->nextCallVoteTimeOut * 1000;
		$account = \DedicatedManager\Services\Account::fromArray($account);
		$system = \DedicatedManager\Services\SystemConfig::fromArray($system);
		
		$this->session->set('options', $options);
		$this->session->set('account', $account);
		$this->session->set('system', $system);
		$this->session->set('isLan', !$isOnline);

		$service = new \DedicatedManager\Services\ConfigFileService();
		if( ($errors = $service->validate($options, $account, $system, !$isOnline)) )
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../configure');
		}

		$this->request->redirectArgList('../match-settings');
	}

	function matchSettings($matchFile = '')
	{
		list(,,$system) = $this->fetchAndAssertConfig(_('setting game options'));
		
		$service = new \DedicatedManager\Services\MatchSettingsFileService();

		if($matchFile)
		{
			list($gameInfos, $maps) = $service->get($matchFile);
			$this->session->set('matchFile', $matchFile);
			$this->session->set('gameInfos', $gameInfos);
			$this->session->set('selected', $maps);
		}
		else
		{
			$gameInfos = new GameInfos();
		}

		$gameInfos = $this->session->get('gameInfos', $gameInfos);

		$this->response->settingsList = $service->getList();
		$this->response->gameInfos = $gameInfos;
		$this->response->scripts = $service->getScriptList($system->title);
		$this->response->title = $system->title;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../configure');
	}

	function saveMatchSettings($gameInfos)
	{
		$this->fetchAndAssertConfig(_('setting game options'));

		$gameInfosObj = GameInfos::fromArray($gameInfos);
		$gameInfosObj->chatTime *= isset($gameInfos['chatTime']) ? 1000 : 1;
		$gameInfosObj->finishTimeout = $gameInfosObj->finishTimeout < 0 ? 1 :
				(isset($gameInfos['finishTimeout']) ? $gameInfosObj->finishTimeout * 1000 : $gameInfosObj->finishTimeout);
		$gameInfosObj->timeAttackLimit = $gameInfosObj->timeAttackLimit < 0 ? 1 :
				(isset($gameInfos['timeAttackLimit']) ? $gameInfosObj->timeAttackLimit * 1000 : $gameInfosObj->timeAttackLimit);
		$gameInfosObj->timeAttackSynchStartPeriod *= isset($gameInfos['timeAttackSynchStartPeriod']) ? 1000 : 1;
		$gameInfosObj->lapsTimeLimit = $gameInfosObj->lapsTimeLimit < 0 ? 1 :
				(isset($gameInfos['lapsTimeLimit']) ? $gameInfosObj->lapsTimeLimit * 1000 : $gameInfosObj->lapsTimeLimit);
		
		$this->session->set('gameInfos', $gameInfosObj);

		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		if( ($errors = $service->validate($gameInfosObj)) )
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../match-settings');
		}

		$this->request->redirectArgList('../select-maps');
	}

	function selectMaps()
	{
		set_time_limit(0);
		list(,,$system) = $this->fetchAndAssertConfig(_('selecting maps'));
		$gameInfos = $this->fetchAndAssertSettings(_('selecting maps'));

		//TODO Find a way to clean this mess
		$environment = $system->title == 'TMCanyon' ? 'Canyon' : 'Storm';

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
		$header->rightLink = $this->request->createLinkArgList('../match-settings');
	}
	
	function saveMaps($selected = '')
	{
		$this->fetchAndAssertConfig(_('selecting maps'));
		$this->fetchAndAssertSettings(_('selecting maps'));
		
		$this->session->set('selected', explode(',', $selected));
		
		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../select-maps/');
		}
		
		$this->request->redirectArgList('../save-files');
	}

	function saveFiles()
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
		$header->rightLink = $this->request->createLinkArgList('../select-maps');
	}

	function startServer($configFile, $matchFile)
	{
		list($options, $account, $system, $isLan) = $this->fetchAndAssertConfig(_('starting it'));
		$gameInfos = $this->fetchAndAssertSettings(_('starting server'));
		$maps = $this->fetchAndAssertMaps(_('starting server'));
		
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
				$service->save($configFile, $options, $account, $system);

				$error = _('An error appeared while writing the MatchSettings file.');
				$service = new \DedicatedManager\Services\MatchSettingsFileService();
				$service->save($matchFile, $gameInfos, $maps);

				$error = _('An error appeared while starting the server and ManiaLive.');
				$service = new \DedicatedManager\Services\ServerService();
				$service->start($configFile, $matchFile, $isLan);
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
			$this->request->redirectArgList('../save-files/');
		}

		$this->session->set('success', _('Your server has been started successfully'));
		$this->goHome();
	}

	function relay($configFile = '')
	{
		$service = new \DedicatedManager\Services\ConfigFileService();

		if($configFile)
		{
			list($options, $account, $system) = $service->get($configFile);
			$this->session->set('configFile', $configFile);
		}
		else
		{
			$options = new \DedicatedManager\Services\ServerOptions();
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
		}
		
		$this->response->configList = $service->getList();
		$this->response->options = $this->session->get('options', $options);
		$this->response->account = $this->session->get('account', $account);
		$this->response->system = $this->session->get('system', $system);
	}

	function saveRelayConfig(array $options, array $account, array $system, $isOnline = 0)
	{
		$options = \DedicatedManager\Services\ServerOptions::fromArray($options);
		$options->callVoteRatio = $options->callVoteRatio < 0 ? $options->callVoteRatio : $options->callVoteRatio / 100;
		$options->nextCallVoteTimeOut = $options->nextCallVoteTimeOut * 1000;
		$account = \DedicatedManager\Services\Account::fromArray($account);
		$system = \DedicatedManager\Services\SystemConfig::fromArray($system);
		
		$this->session->set('options', $options);
		$this->session->set('account', $account);
		$this->session->set('system', $system);
		$this->session->set('isLan', !$isOnline);

		$service = new \DedicatedManager\Services\ConfigFileService();
		if( ($errors = $service->validate($options, $account, $system, !$isOnline)) )
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../relay');
		}

		$this->request->redirectArgList('../save-and-join');
	}

	function saveAndJoin()
	{
		list($options) = $this->fetchAndAssertConfig(_('starting it'), '../relay');

		$defaultFileName = \ManiaLib\Utils\Formatting::stripStyles($options->name);
		$this->response->configFile = $this->session->get('configFile', $defaultFileName);
		$service = new \DedicatedManager\Services\ServerService();
		$this->response->servers = $service->getLives();
		$this->response->spectate = $this->session->get('spectate', new \DedicatedManager\Services\Spectate());
		if(!$this->response->servers)
		{
			$this->response->spectate->method = \DedicatedManager\Services\Spectate::LOGIN;
		}

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to relay server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../relay');
	}

	function startRelay($configFile, $spectate)
	{
		list($options, $account, $system, $isLan) = $this->fetchAndAssertConfig(_('starting it'));
		
		$spectate = \DedicatedManager\Services\Spectate::fromArray($spectate);
		$this->session->set('configFile', $configFile);
		$this->session->set('spectate', $spectate);
		
		$errors = array();
		if(strpbrk($configFile, '\\/:*?"<>|'))
		{
			$errors[] = _('The server config filename must not contain any of the following characters: \\ / : * ? " < > |');
		}
		
		if(!$errors)
		{
			try
			{
				$error = _('An error appeared while writing the server configuration file.');
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->save($configFile, $options, $account, $system);

				$error = _('An error appeared while starting the server and ManiaLive.');
				$service = new \DedicatedManager\Services\ServerService();
				$service->startRelay($configFile, $spectate, $isLan);
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
			$this->request->redirectArgList('../save-and-join/');
		}

		$this->session->set('success', _('Your relay server has been started successfully'));
		$this->goHome();
	}

	function goHome()
	{
		$this->session->delete('options');
		$this->session->delete('account');
		$this->session->delete('system');
		$this->session->delete('isLan');
		$this->session->delete('gameInfos');
		$this->session->delete('selected');
		$this->session->delete('spectate');
		$this->session->delete('configFile');
		$this->session->delete('matchFile');
		$this->request->redirectArgList('/');
	}
	
	private function fetchAndAssertConfig($actionStr, $redirectTo='../configure')
	{
		try
		{
			$options = $this->session->getStrict('options');
			$account = $this->session->getStrict('account');
			$system = $this->session->getStrict('system');
			$isLan = $this->session->get('isLan');
			return array($options, $account, $system, $isLan);
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to configure the server before %s.'), $actionStr));
			$this->request->redirectArgList($redirectTo);
		}
	}
	
	private function fetchAndAssertSettings($actionStr)
	{
		try
		{
			$gameInfos = $this->session->getStrict('gameInfos');
			return $gameInfos;
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to set game options before %s.'), $actionStr));
			$this->request->redirectArgList('../match-settings');
		}
	}
	
	private function fetchAndAssertMaps($actionStr)
	{
		try
		{
			$maps = $this->session->getStrict('selected');
			return $maps;
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to select maps before %s.'), $actionStr));
			$this->request->redirectArgList('../select-maps');
		}
	}
}

?>