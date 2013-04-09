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
		$this->titleList[$title->idString] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormElite@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Elite';
		$title->script = 'Elite.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Elite.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;

		$title = new Title();
		$title->idString = 'SMStormHeroes@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Heroes';
		$title->script = 'Heroes.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Heroes.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;

		$title = new Title();
		$title->idString = 'SMStormJoust@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Joust';
		$title->script = 'Joust.Script.txt';
		$title->mapTypes[] = 'JoustArena';
		$title->filename = 'Joust.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;

		$title = new Title();
		$title->idString = 'Platform@nadeolive';
		$title->game = 'TrackMania';
		$title->name = 'TrackMania Canyon Platform';
		$title->script = 'PlatformMulti.Script.txt';
		$title->mapTypes[] = 'Platform';
		$title->filename = 'Platform.Title.Pack.Gbx';
		$title->environment = 'Canyon';
		$this->titleList[$title->idString] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormRoyal@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Royal';
		$title->script = 'Royal.Script.txt';
		$title->mapTypes[] = 'RoyalArena';
		$title->filename = 'Royal.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormRoyalExperimental@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Royal Experimental';
		$title->script = 'RoyalExp.Script.txt';
		$title->mapTypes[] = 'RoyalArena';
		$title->filename = 'Royal (experimental).Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;
		
		$title = new Title();
		$title->idString = 'SMStormCombo@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Combo';
		$title->script = 'Combo.Script.txt';
		$title->mapTypes[] = 'ComboArena';
		$title->filename = 'Combo.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList[$title->idString] = $title;
		
		//Settings part
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseScriptCallbacks';
		$setting->type = 'boolean';
		$setting->desc = 'Enable script callbacks';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_Mode';
		$setting->type = 'integer';
		$setting->desc = 'Mode 0: classic, 1: free, 2: king';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 60;
		$setting->name = 'S_TimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Attack time limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 15;
		$setting->name = 'S_TimePole';
		$setting->type = 'integer';
		$setting->desc = 'Capture time limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1.5;
		$setting->name = 'S_TimeCapture';
		$setting->type = 'real';
		$setting->desc = 'Capture duration by pole';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 90;
		$setting->name = 'S_WarmUpDuration';
		$setting->type = 'integer';
		$setting->desc = 'Warmup duration (0: disabled)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_MapWin';
		$setting->type = 'integer';
		$setting->desc = 'Number of maps to win a match';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_SubmatchWin';
		$setting->type = 'integer';
		$setting->desc = '(King) Number of matches to win';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_TurnGap';
		$setting->type = 'integer';
		$setting->desc = 'Minimum points gap to win a map';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 8;
		$setting->name = 'S_TurnLimit';
		$setting->type = 'integer';
		$setting->desc = 'Default map tie-break start';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 16;
		$setting->name = 'S_DeciderTurnLimit';
		$setting->type = 'integer';
		$setting->desc = 'Decider map tie-break start';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = '';
		$setting->name = 'S_NeutralEmblemUrl';
		$setting->type = 'string';
		$setting->desc = 'Neutral Emblem Url';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_SleepMultiplier';
		$setting->type = 'real';
		$setting->desc = 'Time between round multiplier';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_MatchmakingSleep';
		$setting->type = 'integer';
		$setting->desc = 'Matchmaking match end duration (-1: infinite)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 6;
		$setting->name = 'S_TurnWin';
		$setting->type = 'integer';
		$setting->desc = 'Map points limit';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseDraft';
		$setting->type = 'boolean';
		$setting->desc = 'Use draft mode before match';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 4;
		$setting->name = 'S_DraftBanNb';
		$setting->type = 'integer';
		$setting->desc = 'Number of map to ban during draft (-1: ban all)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_DraftPickNb';
		$setting->type = 'integer';
		$setting->desc = 'Number of map to pick during draft';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseEliteB2';
		$setting->type = 'boolean';
		$setting->desc = 'Elite Beta 2 gameplay';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_ForceWeaponSelection';
		$setting->type = 'boolean';
		$setting->desc = 'Force the use of the local setting for weapons selection';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseWeaponSelection';
		$setting->type = 'boolean';
		$setting->desc = 'Allow defenders to select their weapons';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_DisplayLaser';
		$setting->type = 'boolean';
		$setting->desc = 'Display the defenders with Laser through walls';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		
		$setting = new ScriptSettings();
		$setting->default = 86400;
		$setting->name = 'S_UseWarmup';
		$setting->type = 'boolean';
		$setting->desc = 'Start with a warmup';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 10;
		$setting->name = 'S_TimePoleElimination';
		$setting->type = 'real';
		$setting->desc = 'Capture time limit after defense elimination';
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 200;
		$setting->name = 'S_MapPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Points to win a map';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 4;
		$setting->name = 'S_OffZoneActivationTime';
		$setting->type = 'integer';
		$setting->desc = 'Offzone activation duration';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 90;
		$setting->name = 'S_OffZoneAutoStartTime';
		$setting->type = 'integer';
		$setting->desc = 'Time before auto activation of the Offzone';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 50;
		$setting->name = 'S_OffZoneTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'OffZone shrink duration';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1.25;
		$setting->name = 'S_OffZoneMaxSpeed';
		$setting->type = 'real';
		$setting->desc = 'Maximum speed multiplier for the OffZone';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 60;
		$setting->name = 'S_EndRoundTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Time limit after the OffZone is completly shrunk';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
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
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_AllowAllies';
		$setting->type = 'boolean';
		$setting->desc = 'Allow early respawn';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 20;
		$setting->name = 'S_EndMapChatTime';
		$setting->type = 'integer';
		$setting->desc = 'End map chat time';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_AllowBeginners';
		$setting->type = 'boolean';
		$setting->desc = 'Is a Beginners Welcome server';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = true;
		$setting->name = 'S_AutoManageAFK';
		$setting->type = 'boolean';
		$setting->desc = 'Switch inactive players to spectators';
		$this->titleList['SMStormRoyal@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'S_AllowDoubleCapture';
		$setting->type = 'boolean';
		$setting->desc = 'Allow a second pole capture after the first activation';
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 8;
		$setting->name = 'S_OffZoneMaxSpeedTime';
		$setting->type = 'integer';
		$setting->desc = 'Duration of capture to reach maximum speed';
		$this->titleList['SMStormRoyalExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 7;
		$setting->name = 'S_RoundPointsToWin';
		$setting->type = 'integer';
		$setting->desc = 'Round points to win';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_RoundPointsGap';
		$setting->type = 'integer';
		$setting->desc = 'Round points gap';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 11;
		$setting->name = 'S_RoundPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Round points limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_RoundTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Round time limit (0: No time limit)';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$comboSettings = clone $setting;
		$comboSettings->default = 300;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $comboSettings;
		
		$setting = new ScriptSettings();
		$setting->default = 60;
		$setting->name = 'S_PoleTimeLimit';
		$setting->type = 'integer';
		$setting->desc = 'Pole capture time limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_MatchPointsToWin';
		$setting->type = 'integer';
		$setting->desc = 'Match points to win';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_MatchPointsGap';
		$setting->type = 'integer';
		$setting->desc = 'Match points gap';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 3;
		$setting->name = 'S_MatchPointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Match points limit';
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 300000;
		$setting->name = 'Duration';
		$setting->type = 'integer';
		$setting->desc = 'Time limit';
		$this->titleList['Platform@nadeolive']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 1;
		$setting->name = 'ShowTop10';
		$setting->type = 'boolean';
		$setting->desc = 'Show Top 10';
		$this->titleList['Platform@nadeolive']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_NbPlayerPerTeam';
		$setting->type = 'integer';
		$setting->desc = 'Number of Players per team (Max. 5)';
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 2;
		$setting->name = 'S_PointsLimit';
		$setting->type = 'integer';
		$setting->desc = 'Points limit (0: No points limit)';
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = false;
		$setting->name = 'S_AllowUnbalancedTeams';
		$setting->type = 'boolean';
		$setting->desc = 'Allow a game to begin without the same number of players in each team';
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = false;
		$setting->name = 'S_UseArmorReduction';
		$setting->type = 'boolean';
		$setting->desc = 'Reduce the armor of players above two armor points';
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = 0;
		$setting->name = 'S_UseLobby';
		$setting->type = 'boolean';
		$setting->desc = 'Launch server in lobby mode';
	
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
                
		$setting = new ScriptSettings();
		$setting->default = 86400;
		$setting->name = 'S_LobbyTimePerMap';
		$setting->type = 'integer';
		$setting->desc = 'Time limit in lobby mode (sec., 0: no limit)';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
			
		$setting = new ScriptSettings();
		$setting->default = false;
		$setting->name = 'S_UsePlayerClublinks';
		$setting->type = 'boolean';
		$setting->desc = 'Use player clublinks';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = '';
		$setting->name = 'S_ForceClublinkTeam1';
		$setting->type = 'boolean';
		$setting->desc = 'Force Team 1 clublink';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
		
		$setting = new ScriptSettings();
		$setting->default = '';
		$setting->name = 'S_ForceClublinkTeam2';
		$setting->type = 'boolean';
		$setting->desc = 'Force Team 2 clublink';
		$this->titleList['SMStormElite@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormEliteExperimental@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormHeroes@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormJoust@nadeolabs']->scriptSettings[$setting->name] = $setting;
		$this->titleList['SMStormCombo@nadeolabs']->scriptSettings[$setting->name] = $setting;
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
	
	function getEnvironment($idString)
	{
		if($this->isCustomTitle($idString))
			return $this->titleList[$idString]->environment;
		return substr($idString, 2);
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