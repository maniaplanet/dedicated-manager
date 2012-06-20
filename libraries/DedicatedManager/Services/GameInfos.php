<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class GameInfos extends \ManiaLive\DedicatedApi\Structures\GameInfos
{

	public $gameMode = \ManiaLive\DedicatedApi\Structures\GameInfos::GAMEMODE_SCRIPT;
	public $scriptName = '';
	public $nbMaps;
	public $chatTime = 10000;
	public $finishTimeout = 1;
	public $allWarmUpDuration = 0;
	public $disableRespawn = 0;
	public $forceShowAllOpponents = 0;
	public $roundsPointsLimit = 50;
	public $roundsForcedLaps = 0;
	public $roundsUseNewRules = 0;
	public $roundsPointsLimitNewRules = 5;
	public $teamPointsLimit = 5;
	public $teamMaxPoints = 6;
	public $teamUseNewRules = 0;
	public $teamPointsLimitNewRules = 5;
	public $timeAttackLimit = 300000;
	public $timeAttackSynchStartPeriod = 0;
	public $lapsNbLaps = 5;
	public $lapsTimeLimit = 0;
	public $cupPointsLimit = 100;
	public $cupRoundsPerMap = 5;
	public $cupNbWinners = 3;
	public $cupWarmUpDuration = 2;

}

?>