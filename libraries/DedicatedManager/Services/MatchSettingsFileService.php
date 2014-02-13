<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

use Maniaplanet\DedicatedServer\Structures\ScriptSettings;

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

		$randomize = (int) $playlist->filter->random_map_order;

		$maps = array();
		for($i = 0; $i < count($playlist->map); $i++)
		{
			$mapIndex = ($i + (int) $playlist->startindex) % count($playlist->map);
			$map = str_replace('\\', '/', (string) $playlist->map[$mapIndex]->file);
			$map = preg_replace('/^\xEF\xBB\xBF/', '', $map);
			$maps[] = $map;
		}

		$scriptSettings = array();
		if($playlist->mode_script_settings)
		{
			foreach($playlist->mode_script_settings->setting as $settings)
			{
				$scriptSetting = new ScriptSettings();
				foreach($settings->attributes() as $key => $value)
				{
					switch($key)
					{
						case 'name':$scriptSetting->name = (string)$value;
							break;
						case 'type':$scriptSetting->type = (string)$value;
							break;
						case 'value':$scriptSetting->default = (string)$value;
							break;
					}
				}
				$scriptSettings[$scriptSetting->name] = $scriptSetting;
			}
		}

		return array($gameInfos, $maps, $scriptSettings, $randomize);
	}

	/**
	 * @param string $filename
	 * @param GameInfos $gameInfos
	 * @param string[] $maps
	 */
	function save($filename, GameInfos $gameInfos, array $maps, array $scriptSettings = array(), $randomize = 0)
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
		$gameSettings->addChild('script_name')->{0} = (string)$gameInfos->scriptName;
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
		$filter->addChild('random_map_order', (int) $randomize);
		$filter->addChild('force_default_gamemode', 0);

		$modeScriptSettings = $playlist->addChild('mode_script_settings');
		foreach($scriptSettings as $scriptSetting)
		{
			/* @var $scriptSetting ScriptSettings */
			$tmp = $modeScriptSettings->addChild('setting');
			$tmp->addAttribute('name', $scriptSetting->name);
			$tmp->addAttribute('type', $scriptSetting->type);
			$tmp->addAttribute('value', $scriptSetting->default);
		}

		$playlist->addChild('startindex', 0);

		foreach($maps as $map)
		{
			$playlist->addChild('map')->addChild('file')->{0} = "\xEF\xBB\xBF".$map;
		}
		$filename = $this->directory.$filename.'.txt';
		$playlist->asXML($filename);
		return $filename;
	}

}

?>
