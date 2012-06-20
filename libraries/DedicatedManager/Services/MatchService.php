<?php
/**
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class MatchService
{

	function getMatchSettingsFilesList()
	{
		$matchSettingsDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';
		$currentDir = getcwd();
		if(!file_exists($matchSettingsDirectory))
		{
			return array();
		}
		chdir($matchSettingsDirectory);

		$matchSettingsFiles = array();
		foreach(glob('*.[tT][xX][tT]') as $file)
		{
			$matchSettingsFiles[] = stristr($file, '.txt', true);
		}
		chdir($currentDir);
		return $matchSettingsFiles;
	}

	function deleteMatchSettingsFilesList(array $files)
	{
		$matchSettingsDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';
		foreach($files as $file)
		{
			if(file_exists($matchSettingsDirectory.$file.'.txt'))
			{
				unlink($matchSettingsDirectory.$file.'.txt');
			}
		}
	}

	function getScriptList($title)
	{
		//TODO Clean this mess with custom titles
		if(preg_match('/(storm){1}$/ixu', $title))
		{
			$game = 'ShootMania';
		}
		elseif(preg_match('/(canyon|valley){1}$/ixu', $title))
		{
			$game = 'TrackMania';
		}
		elseif($title == 'SMStormElite@nadeolabs')
		{
			return array('Elite.Script.txt');
		}
		elseif($title == 'SMStormjoust@nadeolabs')
		{
			return array('Joust.Script.txt');
		}

		$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/Modes/'.$game.'/';
		if(!file_exists($scriptDirectory))
		{
			return array();
		}
		$currentDir = getcwd();
		chdir($scriptDirectory);

		$scripts = glob('*.[sS][cC][rR][iI][pP][tT].[tT][Xx][tT]');
		chdir($currentDir);
		return $scripts;
	}

	function getCurrentMatchRules($hostname, $port)
	{
		$service = new \DedicatedManager\Services\ServerService();
		$server = $service->get($hostname, $port);

		$config = \ManiaLive\Config\Config::getInstance();
		$config->verbose = false;
		$config = \ManiaLive\DedicatedApi\Config::getInstance();
		$config->host = $hostname;
		$config->port = $port;
		$config->password = $server->password;

		$connection = \ManiaLive\DedicatedApi\Connection::getInstance();
		$gameInfo = $connection->getCurrentGameInfo();

		switch($gameInfo->gameMode)
		{
			case GameInfos::GAMEMODE_SCRIPT:
				$values = $connection->getModeScriptSettings();
				$info = $connection->getModeScriptInfo();
				$matchRules = array();
				foreach($info->paramDescs as $value)
				{
					$rule = new RuleDisplayable;
					$rule->label = ($value->desc ? : $value->name);
					$rule->name = $value->name;
					$rule->value = $value->default;
					$matchRules[] = $rule;
				}
				break;
			case GameInfos::GAMEMODE_ROUNDS:
				$matchRules = array();

				$rule = new RuleDisplayable();
				$rule->name = 'roundsPointsLimit';
				$rule->value = (int) $gameInfo->roundsPointsLimit;
				$rule->label = _('Points limit');
				$rule->documentation = _('Limit of points required to win the match.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsForcedLaps';
				$rule->value = (int) $gameInfo->roundsForcedLaps;
				$rule->label = _('Forced laps');
				$rule->documentation = _('Force the number of lap for mutlilaps maps.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsUseNewRules';
				$rule->value = (int) $gameInfo->roundsUseNewRules;
				$rule->label = _('Use new rules');
				$rule->documentation = '';
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsPointsLimitNewRules';
				$rule->value = (int) $gameInfo->roundsPointsLimitNewRules;
				$rule->label = _('Points limit with New Rules');
				$rule->documentation = _('Limit of points required to win the match if new Rules are enabled.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundCustomPoints';
				$rule->value = implode(',', $connection->getRoundCustomPoints());
				$rule->label = _('Custom points');
				$rule->documentation = _('Points that will be given to players in order of arrival.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsUseNewRules';
				$rule->value = (int) $gameInfo->roundsUseNewRules;
				$rule->label = _('Use new rules');
				$rule->inputType = 'radio';
				$rule->inputValues = array(array('label' => _('Yes'), 'value' => 1),
					array('label' => _('No'), 'value' => 0));
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'allWarmUpDuration';
				$rule->value = (int) $gameInfo->allWarmUpDuration;
				$rule->label = _('Warm up duration');
				$rule->documentation = _('0 will disable warm-up, otherwise it\'s the number of rounds.');
				$matchRules[] = $rule;
				break;

			case GameInfos::GAMEMODE_TIMEATTACK:
				$rule = new RuleDisplayable();
				$rule->name = 'timeAttackLimit';
				$rule->value = (int) $gameInfo->timeAttackLimit / 1000;
				$rule->label = _('Time limit in seconds');
				$rule->documentation = _('Map duration.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'timeAttackSynchStartPeriod';
				$rule->value = (int) $gameInfo->timeAttackSynchStartPeriod / 1000;
				$rule->label = _('Synchronisation period at start in seconds');
				$rule->documentation = _('Time of player synchronisation at the beginning of the map.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'allWarmUpDuration';
				$rule->value = (int) $gameInfo->allWarmUpDuration;
				$rule->label = _('Warm up duration');
				$rule->documentation = _('0 will disable warm-up, otherwise it\'s the number of times the gold medal time).');
				$matchRules[] = $rule;
				break;

			case GameInfos::GAMEMODE_TEAM:
				$rule = new RuleDisplayable();
				$rule->name = 'teamPointsLimit';
				$rule->value = (int) $gameInfo->teamPointsLimit;
				$rule->label = _('Points limit');
				$rule->documentation = _('Limit of points required to win the match.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'teamMaxPoints';
				$rule->value = (int) $gameInfo->teamMaxPoints;
				$rule->label = _('Max points');
				$rule->documentation = _('Maximum points that a team can win.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'teamUseNewRules';
				$rule->value = (int) $gameInfo->teamUseNewRules;
				$rule->label = _('Max points');
				$rule->inputType = 'radio';
				$rule->inputValues = array(array('label' => _('Yes'), 'value' => 1),
					array('label' => _('No'), 'value' => 0));
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'teamPointsLimitNewRules';
				$rule->value = (int) $gameInfo->teamPointsLimitNewRules;
				$rule->label = _('Points limit with New Rules');
				$rule->documentation = _('Limit of points required to win the match if new Rules are enabled.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'allWarmUpDuration';
				$rule->value = (int) $gameInfo->allWarmUpDuration;
				$rule->label = _('Warm up duration');
				$rule->documentation = _('0 will disable warm-up, otherwise it\'s the number of rounds.');
				$matchRules[] = $rule;
				break;

			case GameInfos::GAMEMODE_LAPS:
				$rule = new RuleDisplayable();
				$rule->name = 'lapsNbLaps';
				$rule->value = (int) $gameInfo->lapsNbLaps;
				$rule->label = _('Laps number');
				$rule->documentation = _('Number of laps to do before finnishing the race.').' '._("If set to 0, the number laps of the map is used.");
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'lapsTimeLimit';
				$rule->value = (int) $gameInfo->lapsTimeLimit / 1000;
				$rule->label = _('Time limit in seconds');
				$rule->documentation = _('Time allowed for player to do this number of laps.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'allWarmUpDuration';
				$rule->value = (int) $gameInfo->allWarmUpDuration;
				$rule->label = _('Warm up duration');
				$rule->documentation = _('0 will disable warm-up, otherwise it\'s the number of times the gold medal time.');
				$matchRules[] = $rule;
				break;
			case GameInfos::GAMEMODE_CUP:
				$rule = new RuleDisplayable();
				$rule->name = 'cupPointsLimit';
				$rule->value = (int) $gameInfo->cupPointsLimit;
				$rule->label = _('Points limit');
				$rule->documentation = _('Number of point to earn before reaching the finalist status.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'cupRoundsPerMap';
				$rule->value = (int) $gameInfo->cupRoundsPerMap;
				$rule->label = _('Rounds per map');
				$rule->documentation = _('Number of rounds played per map.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'cupNbWinners';
				$rule->value = (int) $gameInfo->cupNbWinners;
				$rule->label = _('Number of winner');
				$rule->documentation = _('Number of player who has to win before the match is complete.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'cupWarmUpDuration';
				$rule->value = (int) $gameInfo->cupWarmUpDuration;
				$rule->label = _('Warm up duration');
				$rule->documentation = _('0 will disable warm-up, otherwise it\'s the number of rounds.');
				$matchRules[] = $rule;
				break;
		}

		return $matchRules;
	}

	function getScriptMapType($scriptName, $title)
	{
		//TODO Clean this mess with custom titles
		if(preg_match('/(storm){1}$/ixu', $title))
		{
			$game = 'ShootMania';
		}
		elseif(preg_match('/(canyon|valley){1}$/ixu', $title))
		{
			$game = 'TrackMania';
		}
		elseif($scriptName == 'Elite.Script.txt' && $title == 'SMStormElite@nadeolabs')
		{
			return array('EliteArena', 'ShootMania\\EliteArena');
		}
		elseif($scriptName == 'Joust.Script.txt' && $title == 'SMStormJoust@nadeolabs')
		{
			return array('JoustArena', 'ShootMania\\JoustArena');
		}

		$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/Modes/'.$game.'/';
		$script = file_get_contents($scriptDirectory.$scriptName);

		$match = array();

		if(!preg_match('/\#Const\\s+(?:CompatibleChallengeTypes|CompatibleMapTypes)\\s*"([^"]*)"/ixu', $script, $match))
		{
			$files = array();
			if(preg_match('/\#Extends\\s+"([^"]*)"/ixu', $script, $files))
			{
				$files = explode(',', $files[1]);
				$scripts = array();
				foreach($files as $file)
				{
					$script = file_get_contents(\DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/'.$file);
					if(preg_match('/\#Const\\s+(?:CompatibleChallengeTypes|CompatibleMapTypes)\\s*"([^"]*)"/ixu', $script, $match))
					{
						$tmp = explode(',', $match[1]);
						$scripts = array_merge($tmp,
							array_map(
								function ($s) use ($game)
								{
									return $game.'\\'.trim($s);
								}, $tmp));
					}
				}
				return $scripts;
			}
			else
			{
				return array();
			}
		}

		$scriptMatch = array();
		preg_match_all('/([^ ,;\t]+)/ixu', $match[1], $scriptMatch);
		$scripts = $scriptMatch[1];
		$scripts = array_merge($scripts,
			array_map(
				function ($s) use ($game)
				{
					return $game.'\\'.trim($s);
				}, $scripts));
		return $scripts;
	}

	function get($filename)
	{
		$matchSettingsDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';
		if(!file_exists($matchSettingsDirectory.$filename.'.txt'))
		{
			throw new \InvalidArgumentException('File does not exists');
		}

		$playlist = simplexml_load_file($matchSettingsDirectory.$filename.'.txt');

		$gameInfos = new GameInfos();
		$gameInfos->gameMode = (int) $playlist->gameinfos->game_mode;
		$gameInfos->chatTime = (int) $playlist->gameinfos->chat_time;
		$gameInfos->finishTimeout = (int) $playlist->gameinfos->finishtimeout;
		$gameInfos->allWarmUpDuration = (int) $playlist->gameinfos->allwarmupduration;
		$gameInfos->disableRespawn = (int) $playlist->gameinfos->disablerespawn;
		$gameInfos->forceShowAllOpponents = (int) $playlist->gameinfos->forceshowallopponents;
		$gameInfos->scriptName = (string) $playlist->gameinfos->script_name;
		$gameInfos->roundsPointsLimit = (int) $playlist->gameinfos->rounds_pointslimit;
		$gameInfos->roundsUseNewRules = (int) $playlist->gameinfos->rounds_usenewrules;
		$gameInfos->roundsForcedLaps = (int) $playlist->gameinfos->rounds_forcedlaps;
		$gameInfos->roundsPointsLimitNewRules = (int) $playlist->gameinfos->rounds_pointslimitnewrules;
		$gameInfos->teamPointsLimit = (int) $playlist->gameinfos->team_pointslimit;
		$gameInfos->teamUseNewRules = (int) $playlist->gameinfos->team_usenew_rules;
		$gameInfos->teamMaxPoints = (int) $playlist->gameinfos->team_maxpoints;
		$gameInfos->teamPointsLimitNewRules = (int) $playlist->gameinfos->team_pointslimitnewrules;
		$gameInfos->timeAttackLimit = (int) $playlist->gameinfos->timeattack_limit;
		$gameInfos->timeAttackSynchStartPeriod = (int) $playlist->gameinfos->timeattack_syncstartperiod;
		$gameInfos->lapsNbLaps = (int) $playlist->gameinfos->laps_nblaps;
		$gameInfos->lapsTimeLimit = (int) $playlist->gameinfos->laps_timelimit;
		$gameInfos->cupPointsLimit = (int) $playlist->gameinfos->cup_pointslimit;
		$gameInfos->cupRoundsPerMap = (int) $playlist->gameinfos->cup_roundsperchallenge;
		$gameInfos->cupNbWinners = (int) $playlist->gameinfos->cup_nbwinners;
		$gameInfos->cupWarmUpDuration = (int) $playlist->gameinfos->cup_warmupduration;

		$maps = array();
		for($i = 0; $i < count($playlist->map); $i++)
		{
			$mapIndex = ($i + (int) $playlist->startindex) % count($playlist->map);
			$maps[] = (string) $playlist->map[$mapIndex]->file;
		}
		return array($gameInfos, $maps);
	}

	function save($filename, GameInfos $gameInfos, array $maps)
	{
		$matchSettingsDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';

		$dom = new \DOMDocument('1.0', 'utf-8');
		$playlist = simplexml_import_dom($dom->createElement('playlist'));

		$gameSettings = $playlist->addChild('gameinfos');
		$gameSettings->addChild('game_mode', $gameInfos->gameMode);
		$gameSettings->addChild('chat_Time', $gameInfos->chatTime);
		$gameSettings->addChild('finishtimeout', $gameInfos->finishTimeout);
		$gameSettings->addChild('allwarmupduration', $gameInfos->allWarmUpDuration);
		$gameSettings->addChild('disablerespawn', $gameInfos->disableRespawn);
		$gameSettings->addChild('forceshowallopponents', $gameInfos->forceShowAllOpponents);
		$gameSettings->addChild('script_name', $gameInfos->scriptName);
		$gameSettings->addChild('rounds_pointslimit', $gameInfos->roundsPointsLimit);
		$gameSettings->addChild('rounds_usenewrules', $gameInfos->roundsUseNewRules);
		$gameSettings->addChild('rounds_forcedlaps', $gameInfos->roundsForcedLaps);
		$gameSettings->addChild('rounds_pointslimitnewrules', $gameInfos->roundsPointsLimitNewRules);
		$gameSettings->addChild('team_pointslimite', $gameInfos->teamPointsLimit);
		$gameSettings->addChild('team_maxpoints', $gameInfos->teamMaxPoints);
		$gameSettings->addChild('team_usenewrules', $gameInfos->teamUseNewRules);
		$gameSettings->addChild('team_pointslimitnewrules', $gameInfos->teamPointsLimitNewRules);
		$gameSettings->addChild('timeattack_limit', $gameInfos->timeAttackLimit);
		$gameSettings->addChild('timeattack_synchstartperiod', $gameInfos->timeAttackSynchStartPeriod);
		$gameSettings->addChild('laps_nblaps', $gameInfos->lapsNbLaps);
		$gameSettings->addChild('laps_timelimit', $gameInfos->lapsTimeLimit);
		$gameSettings->addChild('cup_pointslimit', $gameInfos->cupPointsLimit);
		$gameSettings->addChild('cup_roundsperchallenge', $gameInfos->cupRoundsPerMap);
		$gameSettings->addChild('cup_nbwinners', $gameInfos->cupNbWinners);
		$gameSettings->addChild('cup_warmupduration', $gameInfos->cupWarmUpDuration);

		$hotseat = $playlist->addChild('hotseat');
		$hotseat->addChild('game_mode', 0);
		$hotseat->addChild('time_limit', 300000);
		$hotseat->addChild('rounds_count', 5);

		$filter = $playlist->addChild('filter');
		$filter->addChild('is_lan', 1);
		$filter->addChild('is_internet', 1);
		$filter->addChild('is_solo', 0);
		$filter->addChild('sort_index', 1000);
		$filter->addChild('random_map_order', 0);
		$filter->addChild('force_default_gamemode', 0);

		$playlist->addChild('startindex', 0);

		foreach($maps as $map)
		{
			$playlist->addChild('map')->addChild('file', $map);
		}

		$playlist->asXML($matchSettingsDirectory.$filename.'.txt');
	}

}

?>
