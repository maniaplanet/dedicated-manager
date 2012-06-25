<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

use ManiaLive\DedicatedApi\Structures\GameInfos;

class Edit extends \ManiaLib\Application\Controller implements \ManiaLib\Application\Filterable
{
	/** @var string */
	private $hostname;
	/** @var int */
	private $port;
	/** @var \ManiaLive\DedicatedApi\Connection */
	private $connection;
	private $players;
	private $serverOptions;
	private $currentMap;
	private $nextMap;
	
	protected function onConstruct()
	{
		$this->addFilter($this);
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to server home');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('..', 'hostname', 'port');
	}
	
	function preFilter()
	{
		$this->hostname = $this->request->get('hostname');
		$this->port = $this->request->get('port');
		$this->createConnection();
		
		$rm = new \ReflectionMethod($this, $this->request->getAction('index'));
		$comment = $rm->getDocComment();
		if(!$comment || !preg_match('/@redirect/u', $comment))
		{
			$this->players = $this->connection->getPlayerList(-1, 0);
			$this->serverOptions = $this->connection->getServerOptions();
			$this->currentMap = $this->connection->getCurrentMapInfo();
			$this->nextMap = $this->connection->getNextMapInfo();
		}
	}
	
	private function createConnection()
	{
		$service = new \DedicatedManager\Services\ServerService();
		$server = $service->get($this->hostname, $this->port);
		
		define('APP_ROOT', MANIALIB_APP_PATH);
		\ManiaLive\Utilities\Logger::getLog('Runtime')->disableLog();
		$config = \ManiaLive\Config\Config::getInstance();
		$config->verbose = false;
		$config = \ManiaLive\DedicatedApi\Config::getInstance();
		$config->host = $this->hostname;
		$config->port = $this->port;
		$config->password = $server->password;
		$config->timeout = 3;

		try
		{
			$this->connection = \ManiaLive\DedicatedApi\Connection::getInstance();
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('The server cannot be reached, maybe it\'s closed.'));
			$this->request->redirectArgList('/');
		}
	}
	
	function postFilter()
	{
		$this->response->hostname = $this->hostname;
		$this->response->port = $this->port;
		$this->response->playersCount = count($this->players);
		$this->response->serverOptions = $this->serverOptions;
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
	 */
	function mapAction(array $maps = array(), $nextMapIndex = '', $deleteFilenames = '')
	{
		if(!$maps)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../maps/', 'hostname', 'port');
		}

		if($deleteFilenames)
		{
			$this->connection->removeMapList($maps);
		}
		elseif($nextMapIndex)
		{
			$this->connection->chooseNextMapList($maps);
		}
		$this->request->redirectArgList('../maps/', 'hostname', 'port');
	}

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
		$header->rightLink = $this->request->createLinkArgList('../maps', 'hostname', 'port');
	}

	/**
	 * @redirect
	 */
	function insertMaps($selected = '', $insert = '', $add = '')
	{
		if(!$selected)
		{
			$this->session->set('error', _('You have to select at least one map'));
			$this->request->redirectArgList('../add-maps/', 'hostname', 'port');
		}
		$selected = explode(',', $selected);
		if($insert)
		{
			$this->connection->insertMapList($selected);
		}
		elseif($add)
		{
			$this->connection->addMapList($selected);
		}

		$this->request->redirectArgList('../add-maps/', 'hostname', 'port');
	}

	function rules()
	{
		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		$matchRules = $service->getCurrentMatchRules($this->hostname, $this->port);

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
					$gameInfo->$key = $value;
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
		$this->request->redirectArgList('../rules/', 'hostname', 'port');
	}

	function config()
	{
	}

	/**
	 * @redirect
	 */
	function saveConfig($config)
	{
		$errors = array();
		if($config['name'] === '')
		{
			$errors[] = _('You have to fill the "Name" field');
		}

		if($config['nextMaxPlayers'] <= 0)
		{
			$errors[] = _('You have to set a positive value for the "Max players" field');
		}

		if($config['nextMaxSpectators'] <= 0)
		{
			$errors[] = _('You have to set a positive value for the "Max spectators" field');
		}

		if(($config['callVoteRatio'] != -1 && $config['callVoteRatio'] < 0) || $config['callVoteRatio'] > 100)
		{
			$errors[] = _('The vote ratio has to be between 0 and 100, it can take the value -1 to disable vote');
		}

		if($config['nextMaxSpectators'] + $config['nextMaxPlayers'] > 250)
		{
			$errors[] = _('Too many players. Total must be lower than 250.');
		}
		
		if($errors)
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('/edit/config/', 'hostname', 'port');
		}

		$serverOptions = $this->connection->getServerOptions();
		$serverOptions->name = $config['name'];
		$serverOptions->comment = $config['comment'];
		$serverOptions->nextMaxPlayers = (int) $config['nextMaxPlayers'];
		$serverOptions->password = $config['password'];
		$serverOptions->nextMaxSpectators = (int) $config['nextMaxSpectators'];
		$serverOptions->passwordForSpectator = $config['passwordForSpectator'];
		$serverOptions->hideServer = (int) $config['hideServer'];
		$serverOptions->allowMapDownload = (bool) $config['allowMapDownload'];
		$serverOptions->callVoteRatio = $config['callVoteRatio'] == -1 ? -1 : (float) $config['callVoteRatio'] / 100;
		$serverOptions->nextCallVoteTimeOut = (int) $config['nextCallVoteTimeOut'] * 1000;
		$serverOptions->refereePassword = $config['refereePassword'];
		$serverOptions->refereeMode = (int) $config['refereeMode'];
		$serverOptions->autoSaveReplays = (bool) $config['autosaveReplays'];
		$serverOptions->autoSaveValidationReplays = (bool) $config['autosaveValidationReplays'];
		$serverOptions->nextLadderMode = (int) $config['nextLadderMode'];
		$serverOptions->ladderServerLimitMax = (int) $config['ladderServerLimitMax'];
		$serverOptions->ladderServerLimitMin = (int) $config['ladderServerLimitMin'];

		try
		{
			$this->connection->setServerOptions($serverOptions->toArray());
			$this->session->set('success', _('Configuration successfully changed'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('An error occured while changing server configuration'));
		}
		$this->request->redirectArgList('/edit/config/', 'hostname', 'port');
	}

	function players()
	{
		$this->response->players = $this->players;
	}

	/**
	 * @redirect
	 */
	function actionPlayers(array $players, $kick = '', $ban = '', $blacklist = '', $guestlist = '')
	{
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
		$this->request->redirectArgList('/edit/players/', 'hostname', 'port');
	}

	function banlist()
	{
		$this->response->banlist = $this->connection->getBanList(-1, 0);
		$this->response->players = $this->players;
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to players management');
		$header->rightLink = $this->request->createLinkArgList('../players', 'hostname', 'port');
	}

	/**
	 * @redirect
	 */
	function unban(array $players)
	{
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
		$this->request->redirectArgList('../banlist/', 'hostname', 'port');
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
		$header->rightLink = $this->request->createLinkArgList('../players', 'hostname', 'port');
	}

	/**
	 * @redirect
	 */
	function unblacklist(array $players)
	{
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
		$this->request->redirectArgList('../blacklist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../blacklist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../blacklist/', 'hostname', 'port');
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
			$this->request->redirectArgList('../blacklist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../blacklist/', 'hostname', 'port');
	}

	function guestlist()
	{
		$service = new \DedicatedManager\Services\GuestlistFileService();
		$this->response->guestlistFiles = $service->getList();
		$this->response->guestListedPlayers = $this->connection->getGuestList(-1, 0);
		$this->response->players = $this->players;
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to players management');
		$header->rightLink = $this->request->createLinkArgList('../players', 'hostname', 'port');
	}

	/**
	 * @redirect
	 */
	function unguestlist(array $players)
	{
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
		$this->request->redirectArgList('../guestlist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../guestlist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../guestlist/', 'hostname', 'port');
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
			$this->request->redirectArgList('../guestlist/', 'hostname', 'port');
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
		$this->request->redirectArgList('../guestlist/', 'hostname', 'port');
	}

	function chat()
	{
		$this->response->players = $this->players;
		$this->response->chat = $this->connection->getChatLines();
	}

	/**
	 * @redirect
	 */
	function sendMessage($message,$receiver = null)
	{
		try
		{
			if(!$receiver)
			{
				$receiver = null;
			}
			$this->connection->chatSendServerMessage($message, $receiver);
			$this->session->set('success', _('Your message has been send'));
		}
		catch(\Exception $e)
		{
			\ManiaLib\Application\ErrorHandling::logException($e);
			$this->session->set('error', _('Fail to send your message'));
		}

		$this->request->redirectArgList('../chat/', 'hostname', 'port');
	}

	function teams()
	{
	}

	/**
	 * @redirect
	 */
	function setTeams($team1, $team2)
	{
		$this->connection->setTeamInfo($team1['name'], (double) $team1['color'], $team1['country'], $team2['name'], (double) $team2['color'], $team2['country']);
		$this->session->set('success',_('Changes has been applied'));
		$this->request->redirectArgList('../teams/', 'hostname', 'port');
		
	}

	/**
	 * @redirect
	 */
	function stop()
	{
		$this->connection->stopServer();
		$service = new \DedicatedManager\Services\ServerService();
		$service->delete($this->hostname, $this->port);
		$this->request->redirectArgList('/');
	}

	/**
	 * @redirect
	 */
	function restart()
	{
		$this->connection->restartMap();
		$this->request->redirectArgList('../', 'hostname', 'port');
	}

	/**
	 * @redirect
	 */
	function next()
	{
		$this->connection->nextMap();
		$this->request->redirectArgList('../', 'hostname', 'port');
	}

}

?>