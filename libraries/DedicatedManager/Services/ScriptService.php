<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

use Maniaplanet\DedicatedServer\Structures\ScriptSettings;

class ScriptService
{

	function getActions(\Maniaplanet\DedicatedServer\Connection $connection)
	{
		$infos = $connection->getModeScriptInfo();
		$commandsArray = $infos->commandDescs;
		$commands = array();
		foreach($commandsArray as $command)
		{
			$commands[$command->name] = $command;
		}
		return $commands;
	}

	/**
	 * @param string $host
	 * @param int $port
	 * @return RuleDisplayable[]
	 */
	function getDedicatedMatchRules(\Maniaplanet\DedicatedServer\Connection $connection)
	{
		$gameInfo = $connection->getNextGameInfo();
		$matchRules = array();

		switch($gameInfo->gameMode)
		{
			case GameInfos::GAMEMODE_SCRIPT:
				$info = $connection->getModeScriptInfo();
				$settings = $connection->getModeScriptSettings();
				foreach($info->paramDescs as $value)
				{
					$rule = new RuleDisplayable();
					$rule->name = $value->name;
					$rule->value = $settings[$value->name];
					$rule->label = $value->name;
					$rule->documentation = $value->desc;
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
	function getList($title)
	{
		$titleService = new TitleService();
		if($titleService->isCustomTitle($title))
		{
			return array($titleService->getScript($title));
		}

		$game = $this->getGame($title);
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

	function getFileMatchRules($title, $scriptName = '')
	{
		$titleService = new TitleService();
		if($titleService->isCustomTitle($title))
		{
			return $titleService->getScriptSettings($title);
		}

		$game = $this->getGame($title);
		if(strstr($scriptName, '/') || strstr($scriptName, '\\'))
		{
			$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/';
		}
		else
		{
			$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/Modes/'.$game.'/';
		}
		$script = file_get_contents($scriptDirectory.$scriptName);

		$matchRules = array();

		$files = array();
		if(preg_match('/^\#Extends\\s+"([^"]*)"/ixum', $script, $files))
		{
			$files = explode(',', $files[1]);
			foreach($files as $file)
			{
				$matchRules = array_merge($this->getFileMatchRules($title, $file), $matchRules);
			}
		}

		$match = array();
		if(preg_match_all('/\#Setting\s+(?<name>\S+)\s+(?<value>\S+)\s+(?:as\s*.{0,2}"(?<label>[^"]+)"|.*)/ixu', $script,
				$match))
		{
			foreach($match['name'] as $key => $settingName)
			{
				$rule = new ScriptSettings();
				$rule->name = $settingName;
				$rule->desc = (array_key_exists($key, $match['label']) ? $match['label'][$key] : $settingName);
				$rule->default = $match['value'][$key];
				if($rule->default == 'True' || $rule->default == 'False')
				{
					$rule->default = ($match['value'][$key] == 'True');
					$rule->type = 'boolean';
				}
				elseif(filter_var($rule->default, FILTER_VALIDATE_INT) && !strstr($rule->default,'.'))
					$rule->type = 'integer';
				elseif(filter_var($rule->default, FILTER_VALIDATE_FLOAT))
					$rule->type = 'real';
				else
					$rule->type = 'string';

				$matchRules[$rule->name] = $rule;
			}
		}

		return $matchRules;
	}

	/**
	 * @param string $scriptName
	 * @param string $title
	 * @return string[]
	 */
	function getFileMapType($scriptName, $title)
	{
		$titleService = new TitleService();
		if($titleService->isCustomTitle($title))
		{
			return $titleService->getMapTypes($title);
		}

		$game = $this->getGame($title);
		if(strstr($scriptName,'/') || strstr($scriptName, '\\'))
		{
			$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/';
		}
		else
		{
			$scriptDirectory = \DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Scripts/Modes/'.$game.'/';
		}
		$scriptContent = file_get_contents($scriptDirectory.$scriptName);

		$scripts = array();
		$files = array();
		if(preg_match('/\#Extends\\s+"([^"]*)"/ixu', $scriptContent, $files))
		{
			$files = explode(',', $files[1]);
			foreach($files as $file)
			{
				$scripts = array_merge($this->getFileMapType($file, $title),$scripts);
			}
		}

		$matches = array();
		if(preg_match('/\#Const\\s+(?:CompatibleChallengeTypes|CompatibleMapTypes)\\s*"([^"]*)"/ixu', $scriptContent, $matches))
		{
			$scriptMatches = array();
			preg_match_all('/([^ ,;\t]+)/ixu', $matches[1], $scriptMatches);
			$scripts = $scriptMatches[1];
			$scripts = array_merge($scriptMatches[1],
				array_map(
					function ($s) use ($game)
					{
						return $game.'\\'.trim($s);
					}, $scripts));
		}

		return $scripts;
	}

	private function getGame($title)
	{
		if(preg_match('/^SM(?:Storm)$/ixu', $title))
		{
			return 'ShootMania';
		}
		elseif(preg_match('/^TM(?:Canyon|Valley|Stadium)$/ixu', $title))
		{
			return 'TrackMania';
		}
	}

}

?>
