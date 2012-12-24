<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

use DedicatedApi\Structures\ScriptSettings;

class TitleService
{

	protected $titleList = array();

	function __construct()
	{
		$title = new Title();
		$title->idString = 'SMStormEliteExperimental@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Elite (experimental)';
		$title->script = 'EliteExp.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Elite (experimental).Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormEliteExperimental@nadeolabs'] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormElite@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Elite';
		$title->script = 'Elite.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Elite.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormElite@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'SMStormHeroes@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Heroes';
		$title->script = 'Heroes.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Heroes.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormHeroes@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'SMStormJoust@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Joust';
		$title->script = 'Joust.Script.txt';
		$title->mapTypes[] = 'JoustArena';
		$title->filename = 'Joust.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormJoust@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'Platform@nadeolive';
		$title->game = 'TrackMania';
		$title->name = 'TrackMania Canyon Platform';
		$title->script = 'PlatformMulti.Script.txt';
		$title->mapTypes[] = 'Platform';
		$title->filename = 'Platform.Title.Pack.Gbx';
		$title->environment = 'Canyon';
		$this->titleList['Platform@nadeolive'] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormRoyal@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Royal';
		$title->script = 'Royal.Script.txt';
		$title->mapTypes[] = 'RoyalArena';
		$title->filename = 'Royal.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormRoyal@nadeolabs'] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormRoyalExperimental@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Royal Experimental';
		$title->script = 'RoyalExp.Script.txt';
		$title->mapTypes[] = 'RoyalArena';
		$title->filename = 'Royal (experimental).Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormRoyalExperimental@nadeolabs'] = $title;
		
		//Settings part
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_Mode';
		$setting->type = 'integer';
		$setting->desc = 'Mode 0: classic, 1: free, 2: king';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_Mode'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_Mode'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_Mode'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 60;
		$setting->name = 'S_TimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Attack time limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_TimeLimit'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_TimeLimit'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TimeLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 15;
		$setting->name = 'S_TimePole';
		$setting->type = 'integer';
		$setting->desc = 'Capture time limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_TimePole'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_TimePole'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TimePole'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1.5;
		$setting->name = 'S_TimeCapture';
		$setting->type = 'real';
		$setting->desc = 'Capture duration by pole';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_TimeCapture'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_TimeCapture'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TimeCapture'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 90;
		$setting->name = 'S_WarmUpDuration';
		$setting->type = 'integer';
		$setting->desc = 'Warmup duration (0: disabled)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_WarmUpDuration'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_WarmUpDuration'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_WarmUpDuration'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_MapWin';
		$setting->type = 'integer';
		$setting->desc = 'Number of maps to win a match';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_MapWin'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_MapWin'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_MapWin'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_SubmatchWin';
		$setting->type = 'integer';
		$setting->desc = '(King) Number of matches to win';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_SubmatchWin'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_SubmatchWin'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_SubmatchWin'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 8;
		$setting->name = 'S_TurnLimit';
		$setting->type = 'integer';
		$setting->desc = 'Default map tie-break start';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_TurnLimit'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_TurnLimit'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TurnLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 16;
		$setting->name = 'S_DeciderTurnLimit';
		$setting->type = 'integer';
		$setting->desc = 'Decider map tie-break start';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_DeciderTurnLimit'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_DeciderTurnLimit'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_DeciderTurnLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_UsePlayerClubLinks';
		$setting->type = 'boolean';
		$setting->desc = 'Use player clublinks';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_UsePlayerClubLinks'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_UsePlayerClubLinks'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_UsePlayerClubLinks'] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_UsePlayerClubLinks'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = '';
		$setting->name = 'S_NeutralEmblemUrl';
		$setting->type = 'string';
		$setting->desc = 'Neutral Emblem Url';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_NeutralEmblemUrl'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_NeutralEmblemUrl'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_NeutralEmblemUrl'] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_NeutralEmblemUrl'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_SleepMultiplier';
		$setting->type = 'real';
		$setting->desc = 'Time between round multiplier';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_SleepMultiplier'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_SleepMultiplier'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_SleepMultiplier'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 6;
		$setting->name = 'S_TurnWin';
		$setting->type = 'integer';
		$setting->desc = 'Map points limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_TurnWin'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_TurnWin'] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TurnWin'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseDraft';
		$setting->type = 'boolean';
		$setting->desc = 'Use draft mode before match';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_UseDraft'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_UseDraft'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 4;
		$setting->name = 'S_DraftBanNb';
		$setting->type = 'integer';
		$setting->desc = 'Number of map to ban during draft (-1: ban all)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_DraftBanNb'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_DraftBanNb'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_DraftPickNb';
		$setting->type = 'integer';
		$setting->desc = 'Number of map to pick during draft';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings['S_DraftPickNb'] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings['S_DraftPickNb'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 10;
		$setting->name = 'S_TimePoleElimination';
		$setting->type = 'real';
		$setting->desc = 'Capture time limit after defense elimination';
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings['S_TimePoleElimination'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 200;
		$setting->name = 'S_MapPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Points to win a map';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_MapPointsLimit'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_MapPointsLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 4;
		$setting->name = 'S_OffZoneActivationTime';
		$setting->type = 'integer';
		$setting->desc = 'Offzone activation duration';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_OffZoneActivationTime'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_OffZoneActivationTime'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 90;
		$setting->name = 'S_OffZoneAutoStartTime';
		$setting->type = 'integer';
		$setting->desc = 'Time before auto activation of the Offzone';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_OffZoneAutoStartTime'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_OffZoneAutoStartTime'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 50;
		$setting->name = 'S_OffZoneTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'OffZone shrink duration';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_OffZoneTimeLimit'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_OffZoneTimeLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1.25;
		$setting->name = 'S_OffZoneMaxSpeed';
		$setting->type = 'real';
		$setting->desc = 'Maximum speed multiplier for the OffZone';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_OffZoneMaxSpeed'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_OffZoneMaxSpeed'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 60;
		$setting->name = 'S_EndRoundTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Time limit after the OffZone is completly shrunk';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_EndRoundTimeLimit'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_EndRoundTimeLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 5;
		$setting->name = 'S_SpawnInterval';
		$setting->type = 'integer';
		$setting->desc = 'Time between each wave of spawns';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_SpawnInterval'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_SpawnInterval'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_UseEarlyRespawn';
		$setting->type = 'boolean';
		$setting->desc = 'Allow early respawn';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_UseEarlyRespawn'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_UseEarlyRespawn'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 20;
		$setting->name = 'S_EndMapChatTime';
		$setting->type = 'integer';
		$setting->desc = 'End map chat time';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings['S_EndMapChatTime'] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_EndMapChatTime'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_AllowDoubleCapture';
		$setting->type = 'boolean';
		$setting->desc = 'Allow a second pole capture after the first activation';
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_AllowDoubleCapture'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 8;
		$setting->name = 'S_OffZoneMaxSpeedTime';
		$setting->type = 'integer';
		$setting->desc = 'Duration of capture to reach maximum speed';
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings['S_OffZoneMaxSpeedTime'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 7;
		$setting->name = 'S_RoundPointsToWin';
		$setting->type = 'integer';
		$setting->desc = 'Round points to win';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_RoundPointsToWin'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_RoundPointsGap';
		$setting->type = 'integer';
		$setting->desc = 'Round points gap';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_RoundPointsGap'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 11;
		$setting->name = 'S_RoundPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Round points limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_RoundPointsLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_RoundTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Round time limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_RoundTimeLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_MatchPointsToWin';
		$setting->type = 'integer';
		$setting->desc = 'Match points to win';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_MatchPointsToWin'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_MatchPointsGap';
		$setting->type = 'integer';
		$setting->desc = 'Match points gap';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_MatchPointsGap'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_MatchPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Match points limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_MatchPointsLimit'] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseLobby';
		$setting->type = 'boolean';
		$setting->desc = 'Launch server in lobby mode';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings['S_UseLobby'] = $setting;
	}

	function getList()
	{
		$list = array();
		foreach($this->titleList as $title)
			$filenames[$title->idString] = $title->filename;

		$currentDir = getcwd();
		chdir(\DedicatedManager\Config::getInstance()->dedicatedPath.'UserData/Packs/');

		$files = array();
		foreach(glob('*.[tT][iT][tT][lL][eE].[pP][aA][cC][kK].[gG][bB][xX]') as $file)
		{
			$key = array_keys($filenames, $file);
			if($key)
			{
				$list[] = $this->titleList[end($key)];
			}
		}
		chdir($currentDir);
		return $list;
	}

	function isCustomTitle($idString)
	{
		return array_key_exists($idString, $this->titleList);
	}

	function getMapTypes($idString)
	{
		if(!$this->isCustomTitle($idString)) throw new \InvalidArgumentException();

		$game = $this->titleList[$idString]->game;
		$mapTypes = $this->titleList[$idString]->mapTypes;
		foreach($mapTypes as $mapType)
		{
			$mapTypes[] = $game.'\\'.$mapType;
		}

		return $mapTypes;
	}

	function getScript($idString)
	{
		if(!$this->isCustomTitle($idString)) throw new \InvalidArgumentException();

		return $this->titleList[$idString]->script;
	}
	
	function getScriptSettings($idString)
	{
		if(!$this->isCustomTitle($idString)) throw new \InvalidArgumentException();
		
		return $this->titleList[$idString]->scriptSettings;
	}

}

?>