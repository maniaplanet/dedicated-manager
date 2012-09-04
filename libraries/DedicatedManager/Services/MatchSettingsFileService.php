<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class MatchSettingsFileService extends DedicatedFileService
{
	function __construct()
	{
		$this->directory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';
		$this->rootTag = '<playlist>';
	}
	
	/**
	 * @param GameInfos $gameInfos
	 * @return string[]
	 */
	function validate(GameInfos $gameInfos)
	{
		$errors = array();
		foreach((array) $gameInfos as $key => $value)
		{
			try
			{
				switch($key)
				{
					case 'gameMode':
						\DedicatedManager\Utils\Validation::int($value, 0, 6);
						break;
					case 'roundsUseNewRules':
					case 'teamUseNewRules':
					case 'disableRespawn':
						\DedicatedManager\Utils\Validation::int($value, 0, 1);
						break;
					case 'forceShowAllOpponents':
					case 'chatTime':
					case 'finishTimeout':
					case 'allWarmUpDuration':
					case 'roundsPointsLimit':
					case 'roundsForcedLaps':
					case 'roundsPointsLimitNewRules':
					case 'teamPointsLimit':
					case 'teamMaxPoints':
					case 'teamPointsLimitNewRules':
					case 'timeAttackLimit':
					case 'timeAttackSynchStartPeriod':
					case 'lapsNbLaps':
					case 'lapsTimeLimit':
					case 'cupPointsLimit':
					case 'cupRoundsPerMap':
					case 'cupNbWinners':
					case 'cupWarmUpDuration':
						\DedicatedManager\Utils\Validation::int($value);
						break;
					case 'scriptName':
					default:
						break;
				}
			}
			catch(\Exception $e)
			{
				$errors[] = sprintf(_('Wrong value for field "%s".'), $key);
			}
		}
		
		if($gameInfos->gameMode === GameInfos::GAMEMODE_SCRIPT && $gameInfos->scriptName == '')
		{
			$errors[] = _('You have to select a script to play in script mode.');
		}
		
		return $errors;
	}
	
	/**
	 * @param string $filename
	 * @return mixed[] 2 elements: GameInfos, string[]
	 * @throws \InvalidArgumentException
	 */
	function get($filename)
	{
		if(!file_exists($this->directory.$filename.'.txt'))
		{
			throw new \InvalidArgumentException('File does not exists');
		}

		$playlist = simplexml_load_file($this->directory.$filename.'.txt');

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
			$map = str_replace('\\', '/', (string) $playlist->map[$mapIndex]->file);
			$map = preg_replace('/^\xEF\xBB\xBF/', '', $map);
			$maps[] = $map;
		}
		return array($gameInfos, $maps);
	}

	/**
	 * @param string $filename
	 * @param GameInfos $gameInfos
	 * @param string[] $maps
	 */
	function save($filename, GameInfos $gameInfos, array $maps)
	{
		$this->directory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Maps/MatchSettings/';

		$dom = new \DOMDocument('1.0', 'utf-8');
		$playlist = simplexml_import_dom($dom->createElement('playlist'));

		$gameSettings = $playlist->addChild('gameinfos');
		$gameSettings->addChild('game_mode', (int) $gameInfos->gameMode);
		$gameSettings->addChild('chat_time', (int) $gameInfos->chatTime);
		$gameSettings->addChild('finishtimeout', (int) $gameInfos->finishTimeout);
		$gameSettings->addChild('allwarmupduration', (int) $gameInfos->allWarmUpDuration);
		$gameSettings->addChild('disablerespawn', $gameInfos->disableRespawn);
		$gameSettings->addChild('forceshowallopponents', $gameInfos->forceShowAllOpponents);
		$gameSettings->addChild('script_name', (string) $gameInfos->scriptName);
		$gameSettings->addChild('rounds_pointslimit', (int) $gameInfos->roundsPointsLimit);
		$gameSettings->addChild('rounds_usenewrules', (int) $gameInfos->roundsUseNewRules);
		$gameSettings->addChild('rounds_forcedlaps', (int) $gameInfos->roundsForcedLaps);
		$gameSettings->addChild('rounds_pointslimitnewrules', (int) $gameInfos->roundsPointsLimitNewRules);
		$gameSettings->addChild('team_pointslimit', (int) $gameInfos->teamPointsLimit);
		$gameSettings->addChild('team_maxpoints', (int) $gameInfos->teamMaxPoints);
		$gameSettings->addChild('team_usenewrules', (int) $gameInfos->teamUseNewRules);
		$gameSettings->addChild('team_pointslimitnewrules', (int) $gameInfos->teamPointsLimitNewRules);
		$gameSettings->addChild('timeattack_limit', (int) $gameInfos->timeAttackLimit);
		$gameSettings->addChild('timeattack_synchstartperiod', (int) $gameInfos->timeAttackSynchStartPeriod);
		$gameSettings->addChild('laps_nblaps', (int) $gameInfos->lapsNbLaps);
		$gameSettings->addChild('laps_timelimit', (int) $gameInfos->lapsTimeLimit);
		$gameSettings->addChild('cup_pointslimit', (int) $gameInfos->cupPointsLimit);
		$gameSettings->addChild('cup_roundsperchallenge', (int) $gameInfos->cupRoundsPerMap);
		$gameSettings->addChild('cup_nbwinners', (int) $gameInfos->cupNbWinners);
		$gameSettings->addChild('cup_warmupduration', (int) $gameInfos->cupWarmUpDuration);

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
			$playlist->addChild('map')->addChild('file', "\xEF\xBB\xBF".$map);
			//$playlist->addChild('map')->addChild('file', $map);
		}

		$playlist->asXML($this->directory.$filename.'.txt');
	}

	/**
	 * @param string $host
	 * @param int $port
	 * @return RuleDisplayable[]
	 */
	function getCurrentMatchRules($host, $port)
	{
		$service = new \DedicatedManager\Services\ServerService();
		$server = $service->get($host, $port);
		$connection = \DedicatedApi\Connection::factory($host, $port, 5, 'SuperAdmin', $server->rpcPassword);
		$gameInfo = $connection->getNextGameInfo();
		$matchRules = array();

		switch($gameInfo->gameMode)
		{
			case GameInfos::GAMEMODE_SCRIPT:
				$info = $connection->getModeScriptInfo();
				foreach($info->paramDescs as $value)
				{
					$rule = new RuleDisplayable();
					$rule->name = $value->name;
					$rule->value = $value->default;
					$rule->label = ($value->desc ? : $value->name);
					if($value->type == 'boolean')
					{
						$rule->value = $rule->value == 'True';
						$rule->inputType = 'switch';
						$rule->inputValues = array(
							array('label' => _('No'), 'value' => 0),
							array('label' => _('Yes'), 'value' => 1)
						);
					}
					$matchRules[] = $rule;
				}
				break;
				
			case GameInfos::GAMEMODE_ROUNDS:
				$rule = new RuleDisplayable();
				$rule->name = 'roundsPointsLimit';
				$rule->value = (int) $gameInfo->roundsPointsLimit;
				$rule->label = _('Points limit');
				$rule->documentation = _('Limit of points required to win the match.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsPointsLimitNewRules';
				$rule->value = (int) $gameInfo->roundsPointsLimitNewRules;
				$rule->label = _('Points limit with new rules');
				$rule->documentation = _('Limit of points required to win the match.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsUseNewRules';
				$rule->value = (int) $gameInfo->roundsUseNewRules;
				$rule->label = _('Use new rules');
				$rule->inputType = 'switch';
				$rule->inputValues = array(
					array('label' => _('No'), 'value' => 0),
					array('label' => _('Yes'), 'value' => 1)
				);
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundsForcedLaps';
				$rule->value = (int) $gameInfo->roundsForcedLaps;
				$rule->label = _('Forced laps');
				$rule->documentation = _('Force the number of lap for mutlilaps maps.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'roundCustomPoints';
				$rule->value = implode(',', $connection->getRoundCustomPoints());
				$rule->label = _('Custom points');
				$rule->documentation = _('Points that will be given to players in order of arrival.');
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
				$rule->value = (int) $gameInfo->timeAttackLimit;
				$rule->label = _('Time limit in millisecs');
				$rule->documentation = _('Map duration.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'timeAttackSynchStartPeriod';
				$rule->value = (int) $gameInfo->timeAttackSynchStartPeriod;
				$rule->label = _('Synchronisation period at start in millisecs');
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
				$rule->name = 'teamPointsLimitNewRules';
				$rule->value = (int) $gameInfo->teamPointsLimitNewRules;
				$rule->label = _('Points limit with new rules');
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
				$rule->inputType = 'switch';
				$rule->inputValues = array(
					array('label' => _('No'), 'value' => 0),
					array('label' => _('Yes'), 'value' => 1)
				);
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
				$rule->documentation = _('Number of laps to do before finishing the race, or 0 to use map default.');
				$matchRules[] = $rule;

				$rule = new RuleDisplayable();
				$rule->name = 'lapsTimeLimit';
				$rule->value = (int) $gameInfo->lapsTimeLimit;
				$rule->label = _('Time limit in millisecs');
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
	
	/**
	 * @param string $title
	 * @return string[]
	 */
	function getScriptList($title)
	{
		//TODO Clean this mess with custom titles
		if(preg_match('/(storm){1}$/ixu', $title))
		{
			$game = 'ShootMania';
		}
		else if(preg_match('/(canyon|valley){1}$/ixu', $title))
		{
			$game = 'TrackMania';
		}
		else if($title == 'SMStormElite@nadeolabs')
		{
			return array('Elite.Script.txt');
		}
		else if($title == 'SMStormJoust@nadeolabs')
		{
			return array('Joust.Script.txt');
		}
		else if($title == 'SMStormHeroes@nadeolabs')
		{
			return array('Heroes.Script.txt');
		}
		else if($title == 'Platform@nadeolive')
		{
			return array('PlatformMulti.Script.txt');
		}

		$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/Modes/'.$game.'/';
		if(!file_exists($scriptDirectory))
		{
			return array();
		}
		$currentDir = getcwd();
		chdir($scriptDirectory);

		$scripts = glob('*.[sS][cC][rR][iI][pP][tT].[tT][xX][tT]');
		chdir($currentDir);
		return $scripts;
	}

	/**
	 * @param string $scriptName
	 * @param string $title
	 * @return string[]
	 */
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
			return array('EliteArena', 'ShootMania\\EliteArena', 'HeroesArena', 'ShootMania\\HeroesArena');
		}
		elseif($scriptName == 'Joust.Script.txt' && $title == 'SMStormJoust@nadeolabs')
		{
			return array('JoustArena', 'ShootMania\\JoustArena');
		}
		elseif($scriptName == 'Heroes.Script.txt' && $title == 'SMStormHeroes@nadeolabs')
		{
			return array('HeroesArena', 'ShootMania\\HeroesArena', 'EliteArena', 'ShootMania\\EliteArena');
		}
		elseif($scriptName == 'PlatformMulti.Script.txt' && $title == 'Platform@nadeolive')
		{
			return array('Platform', 'TrackMania\\Platform');
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
}

?>
