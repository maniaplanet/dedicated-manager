<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

use DedicatedApi\Structures\GameInfos;

class Edit extends AbstractController
{

	/** @var \DedicatedManager\Services\Server */
	private $server;

	/** @var \DedicatedApi\Connection */
	private $connection;
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
			$this->server = $service->getDetails($host, $port);
		}
		catch(\Exception $e)
		{
			$service->delete($host, $port);
			$this->session->set('error', _('Unknown server.'));
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

		$this->createConnection();

		$rm = new \ReflectionMethod($this, $this->request->getAction('index'));
		$comment = $rm->getDocComment();
		if($this->server->isRelay && $comment && preg_match('/@norelay/u', $comment))
		{
			$this->session->set('error', _('Unauthorized action on a relay.'));
			if($this->request->getReferer() == $this->request->createLink()) $this->request->redirectArgList('..');
			else $this->request->redirectToReferer();
		}
		if(!$comment || !preg_match('/@redirect/u', $comment))
		{
			$this->request->registerReferer();
			$this->players = $this->connection->getPlayerList(-1, 0);
			$this->options = $this->connection->getServerOptions();
			$this->currentMap = $this->connection->getCurrentMapInfo();
			// TODO remove test when bug on dedicated is fixed
			if(!$this->server->isRelay) $this->nextMap = $this->connection->getNextMapInfo();
		}
	}

	private function createConnection()
	{
		$host = $this->server->rpcHost;
		$port = $this->server->rpcPort;
		$password = $this->server->rpcPassword;
		$timeout = 3;

		$service = new \DedicatedManager\Services\ServerService();
		try
		{
			$this->connection = \DedicatedApi\Connection::factory($host, $port, $timeout, 'SuperAdmin', $password);
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('The server cannot be reached, maybe it\'s closed.'));
			$this->request->redirectArgList('/');
		}
	}

	function postFilter()
	{
		parent::postFilter();

		$this->response->host = $this->server->rpcHost;
		$this->response->port = $this->server->rpcPort;
		$this->response->isRelay = $this->server->isRelay;
		$this->response->maniaplanetJoin = $this->server->getJoinLink();
		$this->response->maniaplanetSpectate = $this->server->getSpectateLink();
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

	function maps()
	{
		$maps = $this->connection->getMapList(-1, 0);
		$this->response->maps = $maps;
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function mapAction(array $maps = array(), $nextMapIndex = '', $deleteFilenames = '')
	{
		if(!$maps)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../maps/', 'host', 'port');
		}

		if($deleteFilenames)
		{
			$this->connection->removeMapList($maps);
		}
		elseif($nextMapIndex)
		{
			$this->connection->chooseNextMapList($maps);
		}
		$this->request->redirectArgList('../maps/', 'host', 'port');
	}

	/**
	 * @norelay
	 */
	function addMaps()
	{
		$maps = $this->connection->getMapList(-1, 0);
		$selected = \ManiaLib\Utils\Arrays::getProperty($maps, 'fileName');
		$selected = array_map(function($s)
			{
				return str_replace('\\', '/', $s);
			}, $selected);

		$matchSettings = $this->connection->getCurrentGameInfo();

		if($matchSettings->gameMode == GameInfos::GAMEMODE_SCRIPT)
		{
			$scriptInfo = $this->connection->getModeScriptInfo();
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
		$header->rightText = _('Back to maps management');
		$header->rightLink = $this->request->createLinkArgList('../maps', 'host', 'port');
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function insertMaps($selected = '', $insert = '', $add = '')
	{
		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../add-maps/', 'host', 'port');
		}
		$selected = explode('|', $selected);
		if($insert)
		{
			$this->connection->insertMapList($selected);
		}
		elseif($add)
		{
			$this->connection->addMapList($selected);
		}

		$this->request->redirectArgList('../add-maps/', 'host', 'port');
	}

	/**
	 * @norelay 
	 */
	function rules()
	{
		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		$matchRules = $service->getCurrentMatchRules($this->server->rpcHost, $this->server->rpcPort);

		$matchInfo = $this->connection->getCurrentGameInfo();
		switch($matchInfo->gameMode)
		{
			case GameInfos::GAMEMODE_SCRIPT:
				$tmp = $this->connection->getModeScriptInfo();
				$gameMode = $tmp->name;
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
			$gameMode = $this->connection->getGameMode();
			if($gameMode == GameInfos::GAMEMODE_SCRIPT)
			{
				$info = $this->connection->getModeScriptInfo();
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
				$this->connection->setModeScriptSettings($rules);
			}
			else
			{
				$gameInfo = $this->connection->getCurrentGameInfo();
				foreach($rules as $key => $value)
					$gameInfo->$key = (int) $value;
				$this->connection->setGameInfos($gameInfo);
			}
			$this->connection->restartMap();
			$this->session->set('success', _('Rules have been successfully changed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while changing rules'));
		}
		$this->request->redirectArgList('../rules/', 'host', 'port');
	}

	function config()
	{
		$this->options->disableHorns = $this->connection->areHornsDisabled();
		$this->response->rpcPassword = $this->server->rpcPassword;
		$systemInfo = $this->connection->getSystemInfo();
		$this->response->connectionRates = array(
			'download' => $systemInfo->connectionDownloadRate,
			'upload' => $systemInfo->connectionUploadRate
		);
	}

	/**
	 * @redirect
	 */
	function saveConfig($options, $rpcPassword, $connectionRates)
	{
		$options = \DedicatedManager\Services\ServerOptions::fromArray($options);
		$options->callVoteRatio = $options->callVoteRatio < 0 ? $options->callVoteRatio : $options->callVoteRatio / 100;
		$options->nextCallVoteTimeOut = $options->nextCallVoteTimeOut * 1000;

		$service = new \DedicatedManager\Services\ConfigFileService();
		if(($errors = $service->validate($options)))
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../config/', 'host', 'port');
		}

		$optionsCur = $this->connection->getServerOptions();
		$options->nextVehicleNetQuality = $optionsCur->nextVehicleNetQuality;
		$options->nextUseChangingValidationSeed = $optionsCur->nextUseChangingValidationSeed;

		try
		{
			$options->ensureCast();
			$this->connection->setServerOptions($options->toArray());
			$this->connection->disableHorns($options->disableHorns);
			if($rpcPassword != $this->server->rpcPassword)
			{
				$this->connection->changeAuthPassword('SuperAdmin', $rpcPassword);
				$service = new \DedicatedManager\Services\ServerService();
				$service->checkConnection($this->server->rpcHost, $this->server->rpcPort, $rpcPassword);
			}
			$this->connection->setConnectionRates((int) $connectionRates['download'], (int) $connectionRates['upload']);
			$this->session->set('success', _('Configuration successfully changed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while changing server configuration'));
		}
		$this->request->redirectArgList('../config/', 'host', 'port');
	}
	
	function votes()
	{
		$tmpRatios = $this->connection->getCallVoteRatios();
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
			$finalRatios[] = array('Command' => $command, 'Ratio' => (double)($ratio < 0 ? -1 : $ratio / 100));
		}
		$this->connection->setCallVoteRatios($finalRatios);
		$this->request->redirectArgList('../votes', 'host', 'port');
	}

	function players()
	{
		$this->response->players = $this->players;
	}

	/**
	 * @redirect
	 */
	function actionPlayers(array $players = array(), $kick = '', $ban = '', $blacklist = '', $guestlist = '')
	{
		if(!$players)
		{
			$this->session->set('error', _('You have to select at least one player.'));
			$this->request->redirectArgList('../players/', 'host', 'port');
		}
		
		if($kick)
		{
			try
			{
				array_map(array($this->connection, 'kick'), $players);
				$this->session->set('success', sprintf(_('Successfully kicked %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while kicking players.'));
			}
		}
		elseif($ban)
		{
			try
			{
				array_map(array($this->connection, 'ban'), $players);
				$this->session->set('success', sprintf(_('Successfully banned %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while banning players.'));
			}
		}
		elseif($blacklist)
		{
			try
			{
				array_map(array($this->connection, 'blackList'), $players);
				$this->session->set('success', sprintf(_('Successfully blacklisted %s'), implode(', ', $players)));
				array_map(array($this->connection, 'kick'), $players);
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while blacklisting players.'));
			}
		}
		elseif($guestlist)
		{
			try
			{
				array_map(array($this->connection, 'addGuest'), $players);
				$this->session->set('success', sprintf(_('Successfully added to guest list %s'), implode(', ', $players)));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$this->session->set('error', _('An error occurred while adding players to the guest list.'));
			}
		}
		$this->request->redirectArgList('../players/', 'host', 'port');
	}

	function banlist()
	{
		$this->response->banlist = $this->connection->getBanList(-1, 0);
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
			$this->session->set('error', _('You have to select at least one player.'));
			$this->request->redirectArgList('../banlist/', 'host', 'port');
		}
		try
		{
			array_map(array($this->connection, 'unBan'), $players);

			$this->session->set('success', sprintf(_('Successfully unban %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while unbanning players.'));
		}
		$this->request->redirectArgList('../banlist/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanBanlist()
	{
		try
		{
			$this->connection->cleanBanList();
			$this->session->set('success', _('Banlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the banlist.'));
		}
		$this->request->redirect('../banlist/');
	}

	function blacklist()
	{
		$service = new \DedicatedManager\Services\BlacklistFileService();
		$this->response->blacklistFiles = $service->getList();
		$this->response->blackListedPlayers = $this->connection->getBlackList(-1, 0);
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
			$this->session->set('error', _('You have to select at least one player.'));
			$this->request->redirectArgList('../blacklist/', 'host', 'port');
		}
		
		try
		{
			array_map(array($this->connection, 'unBlackList'), $players);

			$this->session->set('success', sprintf(_('Successfully unblacklisted %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while unblacklisting players.'));
		}
		$this->request->redirectArgList('../blacklist/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function addBlack($login)
	{
		try
		{
			$this->connection->blackList($login);
			$this->session->set('success', _('player successfully added to blacklist'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while adding the player to blacklist.'));
		}
		$this->request->redirectArgList('../blacklist/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanBlacklist()
	{
		try
		{
			$this->connection->cleanBlackList();
			$this->session->set('success', _('Banlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the banlist.'));
		}
		$this->request->redirect('../blacklist/');
	}

	/**
	 * @redirect
	 */
	function loadBlacklist($filename)
	{
		try
		{
			$this->connection->loadBlackList($filename.'.txt');
			$this->session->set('success', _('Blacklist successfully loaded'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while loading the blacklist.'));
		}
		$this->request->redirectArgList('../blacklist/', 'host', 'port');
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
			$this->request->redirectArgList('../blacklist/', 'host', 'port');
		}
		try
		{
			$this->connection->saveBlackList($filename.'.txt');
			$this->session->set('success', _('Blacklist successfully saved'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while saving the blacklist.'));
		}
		$this->request->redirectArgList('../blacklist/', 'host', 'port');
	}

	function guestlist()
	{
		$service = new \DedicatedManager\Services\GuestlistFileService();
		$this->response->guestlistFiles = $service->getList();
		$this->response->guestListedPlayers = $this->connection->getGuestList(-1, 0);
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
			$this->session->set('error', _('You have to select at least one player.'));
			$this->request->redirectArgList('../guestlist/', 'host', 'port');
		}
		
		try
		{
			array_map(array($this->connection, 'removeGuest'), $players);

			$this->session->set('success', sprintf(_('Successfully removed guests %s'), implode(', ', $players)));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while removing guests.'));
		}
		$this->request->redirectArgList('../guestlist/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function cleanGuestlist()
	{
		try
		{
			$this->connection->cleanGuestList();
			$this->session->set('success', _('Guestlist successfully cleaned'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while cleaning the guestlist.'));
		}
		$this->request->redirect('../guestlist/');
	}

	/**
	 * @redirect
	 */
	function loadGuestlist($filename)
	{
		try
		{
			$this->connection->loadGuestList($filename.'.txt');
			$this->session->set('success', _('Guestlist successfully loaded'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while loading the guestlist.'));
		}
		$this->request->redirectArgList('../guestlist/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function addGuest($login)
	{
		try
		{
			$this->connection->addGuest($login);
			$this->session->set('success', _('Player successfully added to guestlist'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while addind the player to guestlist.'));
		}
		$this->request->redirectArgList('../guestlist/', 'host', 'port');
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
			$this->request->redirectArgList('../guestlist/', 'host', 'port');
		}

		try
		{
			$this->connection->saveGuestList($filename.'.txt');
			$this->session->set('success', _('Guestlist successfully saved'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occurred while saving the guestlist.'));
		}
		$this->request->redirectArgList('../guestlist/', 'host', 'port');
	}

	function chat()
	{
		$this->response->players = $this->players;
		$this->response->chat = $this->connection->getChatLines();
	}

	/**
	 * @redirect
	 */
	function sendMessage($message, $receiver = null)
	{
		try
		{
			$this->connection->chatSendServerMessage($message, $receiver ? : null);
			$this->session->set('success', _('Your message has been sent'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('Fail to send your message'));
		}

		$this->request->redirectArgList('../chat/', 'host', 'port');
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
		$this->connection->setTeamInfo($team1['name'], (double) $team1['color'], $team1['country'], $team2['name'],
			(double) $team2['color'], $team2['country']);
		$this->session->set('success', _('Changes has been applied'));
		$this->request->redirectArgList('../teams/', 'host', 'port');
	}

	function managers()
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this.'));
			$this->request->redirectToReferer();
		}

		$service = new \DedicatedManager\Services\ManagerService();
		$this->response->managers = $service->getByServer($this->server->rpcHost, $this->server->rpcPort);
	}

	/**
	 * @redirect
	 */
	function actionManagers(array $managers, $revoke = '')
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this.'));
			$this->request->redirectToReferer();
		}

		if($revoke)
		{
			$service = new \DedicatedManager\Services\ManagerService();
			foreach($managers as $manager)
				$service->revoke($this->server->rpcHost, $this->server->rpcPort, $manager);
		}
		$this->request->redirectArgList('../managers/', 'host', 'port');
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
			$this->session->set('warning', _('Already a manager.'));
		}
		$this->request->redirectArgList('../managers/', 'host', 'port');
	}

	/**
	 * @redirect
	 */
	function stop()
	{
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to do this.'));
			$this->request->redirectToReferer();
		}

		$this->connection->stopServer();
		$service = new \DedicatedManager\Services\ServerService();
		$service->delete($this->server->rpcHost, $this->server->rpcPort);
		$this->session->set('success', _('Server has been stopped.'));
		$this->request->redirectArgList('/');
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function restart()
	{
		$this->connection->restartMap();
		$this->session->set('success', _('Current map has been restarted.'));
		$this->request->redirectToReferer();
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function next()
	{
		$this->connection->nextMap();
		$this->session->set('success', _('Server is going to the next map.'));
		$this->request->redirectToReferer();
	}

	/**
	 * @redirect
	 * @norelay
	 */
	function balance()
	{
		$this->connection->autoTeamBalance();
		$this->session->set('success', _('Teams has been balanced.'));
		$this->request->redirectToReferer();
	}
}

?>