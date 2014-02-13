<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class GameInfos extends \Maniaplanet\DedicatedServer\Structures\GameInfos
{
	/** @var int */
	public $gameMode = \Maniaplanet\DedicatedServer\Structures\GameInfos::GAMEMODE_SCRIPT;
	/** @var string */
	public $scriptName = '';
	/** @var int */
	public $nbMaps;
	/** @var int */
	public $chatTime = 10000;
	/** @var int */
	public $finishTimeout = 1;
	/** @var int */
	public $allWarmUpDuration = 0;
	/** @var int */
	public $disableRespawn = 0;
	/** @var int */
	public $forceShowAllOpponents = 0;
	/** @var int */
	public $roundsPointsLimit = 50;
	/** @var int */
	public $roundsForcedLaps = 0;
	/** @var int */
	public $roundsUseNewRules = 0;
	/** @var int */
	public $roundsPointsLimitNewRules = 5;
	/** @var int */
	public $teamPointsLimit = 5;
	/** @var int */
	public $teamMaxPoints = 6;
	/** @var int */
	public $teamUseNewRules = 0;
	/** @var int */
	public $teamPointsLimitNewRules = 5;
	/** @var int */
	public $timeAttackLimit = 300000;
	/** @var int */
	public $timeAttackSynchStartPeriod = 0;
	/** @var int */
	public $lapsNbLaps = 5;
	/** @var int */
	public $lapsTimeLimit = 0;
	/** @var int */
	public $cupPointsLimit = 100;
	/** @var int */
	public $cupRoundsPerMap = 5;
	/** @var int */
	public $cupNbWinners = 3;
	/** @var int */
	public $cupWarmUpDuration = 2;

}

?>