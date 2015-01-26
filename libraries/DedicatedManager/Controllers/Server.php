<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

use Maniaplanet\DedicatedServer\Structures\GameInfos;

class Server extends AbstractController
{
	/** @var \DedicatedManager\Services\Server */
	private $server;
	private $players;
	private $options;
	private $currentMap;
	private $nextMap;

	protected function onConstruct()
	{
		parent::onConstruct();

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server home');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('..', 'host', 'port');
	}

	function preFilter()
	{
		parent::preFilter();

		$host = $this->request->get('host');
		$port = $this->request->get('port');

		$service = new \DedicatedManager\Services\ServerService();
		try
		{
			$this->server = $service->get($host, $port);
			$this->server->fetchDetails();
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('The server is unreachable, maybe it is closed'));
			$this->request->redirectArgList('/');
		}

		if(!$this->isAdmin)
		{
			$service = new \DedicatedManager\Services\ManagerService();
			if(!$service->isAllowed($host, $port, $this->session->login))
			{
				$this->session->set('error', _('You\'re not allowed to edit this server.'));
				$this->request->redirectArgList('/');
			}
		}

		$rm = new \ReflectionMethod($this, $this->request->getAction('index'));
		$comment = $rm->getDocComment();
		if($this->server->isRelay && $comment && preg_match('/@norelay/u', $comment))
		{
			$this->session->set('error', _('Unauthorized action on a relay.'));
			if($this->request->getReferer() == $this->request->createLink()) $this->request->redirectArgList('..');
			else $this->request->redirectToReferer();
		}

		$mapDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/';
		if(!in_array($this->server->rpcHost, array('127.0.0.1', 'localhost')) && $this->server->connection->getMapsDirectory() != $mapDirectory)
		{
			if(preg_match('/@local/u', $comment))
			{
				$this->session->set('error', _('Unauthorized action on a distant server.'));
				if($this->request->getReferer() == $this->request->createLink()) $this->request->redirectArgList('..');
				else $this->request->redirectToReferer();
			}
			$this->response->isLocal = false;
		}
		else
		{
			$this->response->isLocal = true;
		}

		if(!$comment || !preg_match('/@redirect/u', $comment))
		{
			$this->request->registerReferer();
			$this->players = $this->server->connection->getPlayerList(-1, 0);
			$this->options = $this->server->connection->getServerOptions();
			$this->currentMap = $this->server->connection->getCurrentMapInfo();
			if(!$this->server->isRelay) $this->nextMap = $this->server->connection->getNextMapInfo();
		}
	}

	function postFilter()
	{
		parent::postFilter();

		$this->response->host = $this->server->rpcHost;
		$this->response->port = $this->server->rpcPort;
		$this->response->isRelay = $this->server->isRelay;
		$this->response->maniaplanetJoin = $this->server->getLink('join');
		$this->response->maniaplanetSpectate = $this->server->getLink('spectate');
		$this->response->playersCount = count($this->players);
		$this->response->options = $this->options;
		$this->response->currentMap = $this->currentMap;
		$this->response->nextMap = $this->nextMap;
	}

	function index()
	{
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightLink = null;
	}

	/**
	 * @norelay
	 */
	function maps()
	{
		$this->response->maps = $this->server->connection->getMapList(-1, 0);
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function doMaps(array $maps = array(), $nextList = null, $delete = null, $jumpList = null)
	{
		if(!$maps)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../maps', 'host', 'port');
		}

		if($delete)
		{
			$this->server->connection->removeMapList($maps);
			$message = _('Map has been successfully removed from map list','Map has been successfully removed from map list', count($maps));
		}
		elseif($nextList)
		{
			$this->server->connection->chooseNextMapList($maps);
			$message = _('Map order has been changed successfully');
		}
		elseif($jumpList)
		{
			$map = array_shift($maps);
			$mapList = $this->server->connection->getMapList(-1, 0);
			do
			{
				$current = current($mapList);
			}while($current->fileName !== $map && next($mapList) !== false);

			$key = key($mapList);
			$this->server->connection->jumpToMapIndex($key);
			$message = _('The server is jumping to the map');
		}
		$this->session->set('success', $message);
		$this->request->redirectArgList('../maps', 'host', 'port');
	}

	/**
	 * @norelay
	 * @local
	 */
	function addMaps()
	{
		$maps = $this->server->connection->getMapList(-1, 0);
		$selected = \ManiaLib\Utils\Arrays::getProperty($maps, 'fileName');
		$selected = array_map(function($s)
			{
				$s = preg_replace('/^\xEF\xBB\xBF/', '', $s);
				return str_replace('\\', '/', $s);
			}, $selected);

		$matchSettings = $this->server->connection->getNextGameInfo();

		if($matchSettings->gameMode == GameInfos::GAMEMODE_SCRIPT)
		{
			$scriptInfo = $this->server->connection->getModeScriptInfo();
			$type = explode(',', $scriptInfo->compatibleMapTypes);
			$isLaps = false;
		}
		else
		{
			$type = array('Race');
			$isLaps = $matchSettings->gameMode == GameInfos::GAMEMODE_LAPS;
		}

		$service = new \DedicatedManager\Services\MapService();
		$files = $service->getList('', true, $isLaps, $type, $this->currentMap->environnement);

		$this->response->files = $files;
		$this->response->selected = $selected;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to maps list');
		$header->rightLink = $this->request->createLinkArgList('../maps', 'host', 'port');
	}

	/**
	 * @redirect
	 * @norelay
	 * @local
	 */
	function doAddMaps($selected = '', $insert = '', $add = '')
	{
		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../add-maps', 'host', 'port');
		}
		$selected = explode('|', $selected);
		$selected = array_map(function ($s) { return "\xEF\xBB\xBF".$s; }, $selected);
		if($insert)
		{
			$this->server->connection->insertMapList($selected);
		}
		elseif($add)
		{
			$this->server->connection->addMapList($selected);
		}

		$this->request->redirectArgList('../maps', 'host', 'port');
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function saveMatchSettings($filename)
	{
		if(strpbrk($filename, '\\/:*?"<>|'))
		{
			$this->session->set('error', _('The filename must not contain any of the following characters: \\ / : * ? " < > |'));
			$this->request->redirectArgList('../maps', 'host', 'port');
		}
		try
		{
			$this->server->connection->saveMatchSettings('MatchSettings/'.$filename.'.txt');
			$this->session->set('success', _('Match settings successfully saved'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('Error while saving match settings'));
		}
		$this->request->redirectArgList('../maps', 'host', 'port');
	}

	/**
	 * @norelay
	 */
	function rules()
	{
		$service = new \DedicatedManager\Services\ScriptService();
		$matchRules = $service->getDedicatedMatchRules($this->server->connection);

		$matchInfo = $this->server->connection->getNextGameInfo();
		switch($matchInfo->gameMode)
		{
			case GameInfos::GAMEMODE_SCRIPT:
				$gameMode = $this->server->connection->getModeScriptInfo()->name;
				break;
			case GameInfos::GAMEMODE_ROUNDS:
				$gameMode = _('Round');
				break;
			case GameInfos::GAMEMODE_TIMEATTACK:
				$gameMode = _('Time attack');
				break;
			case GameInfos::GAMEMODE_TEAM:
				$gameMode = _('Team');
				break;
			case GameInfos::GAMEMODE_LAPS:
				$gameMode = _('Lap');
				break;
			case GameInfos::GAMEMODE_CUP:
				$gameMode = _('Cup');
				break;
		}

		$this->response->gameMode = $gameMode;
		$this->response->matchRules = $matchRules;
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function setRules(array $rules)
	{
		try
		{
			$gameMode = $this->server->connection->getGameMode();
			if($gameMode == GameInfos::GAMEMODE_SCRIPT)
			{
				$info = $this->server->connection->getModeScriptInfo();
				foreach($info->paramDescs as $value)
				{
					switch($value->type)
					{
						case 'int':
							$rules[$value->name] = (int) $rules[$value->name];
							break;
						case 'double':
							$rules[$value->name] = (double) $rules[$value->name];
							break;
						case 'boolean':
							$rules[$value->name] = (bool) $rules[$value->name];
							break;
						case 'string':
						default:
							$rules[$value->name] = (string) $rules[$value->name];
					}
				}
				$this->server->connection->setModeScriptSettings($rules);
			}
			else
			{
				$gameInfo = $this->server->connection->getCurrentGameInfo();
				foreach($rules as $key => $value)
					$gameInfo->$key = (int) $value;
				$this->server->connection->setGameInfos($gameInfo);
			}
			$this->server->connection->restartMap();
			$this->session->set('success', _('Rules have been successfully changed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while changing rules'));
		}
		$this->request->redirectArgList('../rules', 'host', 'port');
	}

	function commands()
	{
		$service = new \DedicatedManager\Services\ScriptService();
		if($this->server->connection->getCurrentGameInfo()->gameMode == GameInfos::GAMEMODE_SCRIPT)
		{
			$matchCommands = $service->getActions($this->server->connection);
			$this->response->matchCommands = $matchCommands;
		}
		else
		{
			$this->session->set('error', _('The current server is not in script Mode'));
			$this->request->redirectToReferer();
		}
	}

	function setCommands($commands)
	{
		try
		{
			$service = new \DedicatedManager\Services\ScriptService();
			$matchCommands = $service->getActions($this->server->connection);
			foreach($commands as $key => $value)
			{
				switch ($matchCommands[$key]->type)
				{
					case 'int':
						$commands[$key] = (int) $value;
						break;
					case 'double':
						$commands[$key] = (double) $value;
						break;
					case 'boolean':
						$commands[$key] = (bool) $value;
						break;
					case 'string':
					default:
						$commands[$key] = (string) $value;
				}
			}
			$this->server->connection->sendModeScriptCommands($commands);
			$this->session->set('success', _('Commands successfully passed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while setting commands'));
		}
		$this->request->redirectArgList('../commands', 'host', 'port');
	}

	function config()
	{
		$this->options->disableHorns = $this->server->connection->areHornsDisabled();
		$this->response->rpcPassword = $this->server->rpcPassword;
		$systemInfo = $this->server->connection->getSystemInfo();
		$this->response->connectionRates = array(
			'download' => $systemInfo->connectionDownloadRate,
			'upload' => $systemInfo->connectionUploadRate
		);
	}

	/**
	 * @redirect
	 */
	function setConfig($options, $rpcPassword, $connectionRates)
	{
		$options = \DedicatedManager\Services\ServerOptions::fromArray($options);
		$options->callVoteRatio = $options->callVoteRatio < 0 ? $options->callVoteRatio : $options->callVoteRatio / 100;
		$options->nextCallVoteTimeOut = $options->nextCallVoteTimeOut * 1000;

		$service = new \DedicatedManager\Services\ConfigFileService();
		if(($errors = $service->validate($options)))
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../config', 'host', 'port');
		}

		$optionsCur = $this->server->connection->getServerOptions();
		$options->nextVehicleNetQuality = $optionsCur->nextVehicleNetQuality;
		$options->nextUseChangingValidationSeed = $optionsCur->nextUseChangingValidationSeed;

		try
		{
			$options->ensureCast();
			$this->server->connection->setServerOptions($options->toArray());
			$this->server->connection->disableHorns($options->disableHorns);
			$this->server->connection->setConnectionRates((int) $connectionRates['download'], (int) $connectionRates['upload']);
			if($rpcPassword != $this->server->rpcPassword)
			{
				$this->server->connection->changeAuthPassword('SuperAdmin', $rpcPassword);
				$this->server->closeConnection();
				$this->server->rpcPassword = $rpcPassword;
				$service = new \DedicatedManager\Services\ServerService();
				$service->register($this->server);
			}
			$this->session->set('success', _('Configuration successfully changed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while changing server configuration'));
		}
		$this->request->redirectArgList('../config', 'host', 'port');
	}

	function votes()
	{
		$tmpRatios = $this->server->connection->getCallVoteRatios();
		$ratios = array();
		foreach($tmpRatios as $ratio)
		{
			$ratios[$ratio['Command']] = $ratio['Ratio'] < 0 ? -1 : $ratio['Ratio'] * 100;
		}
		$this->response->ratios = $ratios;
	}

	/**
	 * @redirect
	 */
	function updateVotes(array $ratios)
	{
		$finalRatios = array();
		foreach($ratios as $command => $ratio)
		{
			$finalRatios[] = array('Command' => $command, 'Ratio' => (double) ($ratio < 0 ? -1 : $ratio / 100));
		}
		$this->server->connection->setCallVoteRatios($finalRatios);
		$this->session->set('success', _('Vote ratios successfully changed'));
		$this->request->redirectArgList('../votes', 'host', 'port');
	}

	function players()
	{
		$this->response->players = $this->players;
	}

	/**
	 * @redirect
	 */
	function doPlayers(array $players = array(), $kick = '', $ban = '', $blacklist = '', $guestlist = '')
	{
		if(!$players)
		{
			$this->session->set('error', _('You have to select at least one player'));
			$this->request->redirectArgList('../players', 'host', 'port');
		}

		if($kick)
		{
			try
			{
				array_map(array($this->server->connection, 'kick'), $players);
				$this->session->set('success', sprintf(_('Successfully kicked %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while kicking players'));
			}
		}
		elseif($ban)
		{
			try
			{
				array_map(array($this->server->connection, 'ban'), $players);
				$this->session->set('success', sprintf(_('Successfully banned %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while banning players'));
			}
		}
		elseif($blacklist)
		{
			try
			{
				array_map(array($this->server->connection, 'blackList'), $players);
				$this->session->set('success', sprintf(_('Successfully blacklisted %s'), implode(', ', $players)));
				array_map(array($this->server->connection, 'kick'), $players);
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while blacklisting players'));
			}
		}
		elseif($guestlist)
		{
			try
			{
				array_map(array($this->server->connection, 'addGuest'), $players);
				$this->session->set('success', sprintf(_('Successfully added to guest list %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while adding players to the guest list'));
			}
		}
		$this->request->redirectArgList('../players', 'host', 'port');
	}

	function banlist()
	{
		$this->response->banlist = $this->server->connection->getBanList(-1, 0);
		$this->response->players = $this->players;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to players management');
		$header->rightLink = $this->request->createLinkArgList('../players', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function unban(array $players = array())
	{
		if(!$players)
		{
			$this->session->set('error', _('You have to select at least one player'));
			$this->request->redirectArgList('../banlist', 'host', 'port');
		}
		try
		{
			array_map(array($this->server->connection, 'unBan'), $players);

			$this->session->set('success', sprintf(_('Successfully unban %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while unbanning players'));
		}
		$this->request->redirectArgList('../banlist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanBanlist()
	{
		try
		{
			$this->server->connection->cleanBanList();
			$this->session->set('success', _('Banlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the banlist'));
		}
		$this->request->redirect('../banlist');
	}

	function blacklist()
	{
		$service = new \DedicatedManager\Services\BlacklistFileService();
		if($this->response->isLocal)
		{
			$this->response->blacklistFiles = $service->getList();
		}
		else
		{
			$this->response->blacklistFiles = array();
		}
		$this->response->blackListedPlayers = $this->server->connection->getBlackList(-1, 0);
		$this->response->players = $this->players;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to players management');
		$header->rightLink = $this->request->createLinkArgList('../players', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function unblacklist(array $players)
	{
		if(!$players)
		{
			$this->session->set('error', _('You have to select at least one player'));
			$this->request->redirectArgList('../blacklist', 'host', 'port');
		}

		try
		{
			array_map(array($this->server->connection, 'unBlackList'), $players);

			$this->session->set('success', sprintf(_('Successfully unblacklisted %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while unblacklisting players'));
		}
		$this->request->redirectArgList('../blacklist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function addBlack($login)
	{
		try
		{
			$this->server->connection->blackList($login);
			$this->session->set('success', _('player successfully added to blacklist'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while adding the player to blacklist'));
		}
		$this->request->redirectArgList('../blacklist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanBlacklist()
	{
		try
		{
			$this->server->connection->cleanBlackList();
			$this->session->set('success', _('Banlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the banlist'));
		}
		$this->request->redirect('../blacklist');
	}

	/**
	 * @redirect
	 * @local
	 */
	function loadBlacklist($filename)
	{
		try
		{
			$this->server->connection->loadBlackList($filename.'.txt');
			$this->session->set('success', _('Blacklist successfully loaded'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while loading the blacklist'));
		}
		$this->request->redirectArgList('../blacklist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function saveBlacklist($filename)
	{
		$service = new \DedicatedManager\Services\ConfigFileService();
		$configList = $service->getList();
		if(in_array($filename, $configList))
		{
			$this->session->set('error', _('You cannot use this filename'));
			$this->request->redirectArgList('../blacklist', 'host', 'port');
		}
		try
		{
			$this->server->connection->saveBlackList($filename.'.txt');
			$this->session->set('success', _('Blacklist successfully saved'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while saving the blacklist'));
		}
		$this->request->redirectArgList('../blacklist', 'host', 'port');
	}

	function guestlist()
	{
		$service = new \DedicatedManager\Services\GuestlistFileService();
		if($this->response->isLocal)
		{
			$this->response->guestlistFiles = $service->getList();
		}
		else
		{
			$this->response->guestlistFiles = array();
		}
		$this->response->guestListedPlayers = $this->server->connection->getGuestList(-1, 0);
		$this->response->players = $this->players;

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to players management');
		$header->rightLink = $this->request->createLinkArgList('../players', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function unguestlist(array $players)
	{
		if(!$players)
		{
			$this->session->set('error', _('You have to select at least one player'));
			$this->request->redirectArgList('../guestlist', 'host', 'port');
		}

		try
		{
			array_map(array($this->server->connection, 'removeGuest'), $players);

			$this->session->set('success', sprintf(_('Successfully removed guests %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while removing guests'));
		}
		$this->request->redirectArgList('../guestlist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanGuestlist()
	{
		try
		{
			$this->server->connection->cleanGuestList();
			$this->session->set('success', _('Guestlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the guestlist'));
		}
		$this->request->redirect('../guestlist');
	}

	/**
	 * @redirect
	 * @local
	 */
	function loadGuestlist($filename)
	{
		try
		{
			$this->server->connection->loadGuestList($filename.'.txt');
			$this->session->set('success', _('Guestlist successfully loaded'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while loading the guestlist'));
		}
		$this->request->redirectArgList('../guestlist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function addGuest($login)
	{
		try
		{
			$this->server->connection->addGuest($login);
			$this->session->set('success', _('Player successfully added to guestlist'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while addind the player to guestlist'));
		}
		$this->request->redirectArgList('../guestlist', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function saveGuestlist($filename)
	{
		$service = new \DedicatedManager\Services\ConfigFileService();
		$configList = $service->getList();
		if(in_array($filename, $configList))
		{
			$this->session->set('error', _('You cannot use this filename'));
			$this->request->redirectArgList('../guestlist', 'host', 'port');
		}

		try
		{
			$this->server->connection->saveGuestList($filename.'.txt');
			$this->session->set('success', _('Guestlist successfully saved'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while saving the guestlist'));
		}
		$this->request->redirectArgList('../guestlist/', 'host', 'port');
	}

	function chat()
	{
		$this->response->players = $this->players;
//		$this->response->chat = $this->connection->getChatLines();
	}

	/**
	 * @redirect
	 */
	function chatDisplay()
	{
		$this->response->chat = $this->server->connection->getChatLines();
	}

	/**
	 * @redirect
	 */
	function sendMessage($message, $receiver = null)
	{
		try
		{
			$this->server->connection->chatSendServerMessage($message, $receiver ? : null);
			$this->session->set('success', _('Your message has been sent'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('Fail to send your message'));
		}

		$this->request->redirectArgList('../chat', 'host', 'port');
	}

	/**
	 * @norelay
	 */
	function teams()
	{

	}

	/**
	 * @redirect
	 * @norelay
	 */
	function setTeams($team1, $team2)
	{
		$this->server->connection->setTeamInfo($team1['name'], (double) $team1['color'], $team1['country'], $team2['name'], (double) $team2['color'], $team2['country']);
		$this->session->set('success', _('Changes has been applied'));
		$this->request->redirectArgList('../teams', 'host', 'port');
	}

	function managers()
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this'));
			$this->request->redirectToReferer();
		}

		$service = new \DedicatedManager\Services\ManagerService();
		$this->response->managers = $service->getByServer($this->server->rpcHost, $this->server->rpcPort);
	}

	/**
	 * @redirect
	 */
	function doManagers(array $managers, $revoke = '')
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this'));
			$this->request->redirectToReferer();
		}

		if($revoke)
		{
			$service = new \DedicatedManager\Services\ManagerService();
			foreach($managers as $manager)
				$service->revoke($this->server->rpcHost, $this->server->rpcPort, $manager);
		}
		$this->request->redirectArgList('../managers', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function addManager($login)
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this.'));
			$this->request->redirectToReferer();
		}

		$service = new \DedicatedManager\Services\ManagerService();
		try
		{
			$service->grant($this->server->rpcHost, $this->server->rpcPort, $login);
		}
		catch(\Exception $e)
		{
			$this->session->set('warning', _('Already a manager'));
		}
		$this->request->redirectArgList('../managers', 'host', 'port');
	}

	function controllers()
	{
		$this->server->connection->enableCallbacks(true);
		$this->server->connection->dedicatedEcho('DedicatedManager '.DEDICATED_MANAGER_VERSION, '?census');
		// waiting for answers
		usleep(500000);
		$controllers = array();
		foreach($this->server->connection->executeCallbacks() as $call)
		{
			if($call[0] == 'ManiaPlanet.Echo' && $call[1][0] == '!census:DedicatedManager '.DEDICATED_MANAGER_VERSION)
			{
				$controllers[] = $call[1][1];
				if(stripos($call[1][1], 'ManiaLive') !== false)
					$this->response->manialiveStarted = true;
			}
		}
		$this->response->controllers = array_unique($controllers);
	}

	/**
	 * @redirect
	 */
	function stopControllers($controllers=array())
	{
		if(!$controllers)
		{
			$this->session->set('error', _('You have to select at least one controller'));
			$this->request->redirectArgList('../controllers', 'host', 'port');
		}

		foreach($controllers as $controller)
		{
			$this->server->connection->dedicatedEcho('DedicatedManager '.DEDICATED_MANAGER_VERSION, '?stop:'.$controller);
		}
		$this->session->set('success', _('Controllers have been asked to stop successfully'));
		$this->request->redirectArgList('../controllers', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function stop()
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this'));
			$this->request->redirectToReferer();
		}

		$this->server->connection->stopServer();
		$service = new \DedicatedManager\Services\ServerService();
		$service->delete($this->server->rpcHost, $this->server->rpcPort);
		$this->session->set('success', _('Server has been stopped'));
		$this->request->redirectArgList('/');
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function restart()
	{
		$this->server->connection->restartMap();
		$this->session->set('success', _('Current map has been restarted'));
		$this->request->redirectToReferer();
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function next()
	{
		$this->server->connection->nextMap();
		$this->session->set('success', _('Server is going to the next map'));
		$this->request->redirectToReferer();
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function balance()
	{
		$this->server->connection->autoTeamBalance();
		$this->session->set('success', _('Teams has been balanced'));
		$this->request->redirectToReferer();
	}

}

?>
