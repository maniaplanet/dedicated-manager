<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

class ServerOptions extends \ManiaLive\DedicatedApi\Structures\ServerOptions
{
	public $name;
	public $comment;
	public $password;
	public $passwordForSpectator;
	public $hideServer = 0;
	public $currentMaxPlayers = 16;
	public $nextMaxPlayers = 16;
	public $currentMaxSpectators = 8;
	public $nextMaxSpectators = 8;
	public $isP2PUpload = true;
	public $isP2PDownload = true;
	public $currentLadderMode = 1;
	public $nextLadderMode = 1;
	public $ladderServerLimitMax = 50000;
	public $ladderServerLimitMin = 0;
	public $currentVehicleNetQuality = 1;
	public $nextVehicleNetQuality = 1;
	public $currentCallVoteTimeOut = 60000;
	public $nextCallVoteTimeOut = 60000;
	public $callVoteRatio = 0.5;
	public $allowMapDownload = false;
	public $autoSaveReplays = false;
	public $autoSaveValidationReplays = false;
	public $refereePassword;
	public $refereeMode = 0;
	public $currentUseChangingValidationSeed = 0;
	public $nextUseChangingValidationSeed = 0;
}

?>