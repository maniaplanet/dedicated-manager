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
	public $nextMaxPlayers = 16;
	public $nextMaxSpectators = 8;
	public $nextLadderMode = true;
	public $ladderServerLimitMax = 50000;
	public $ladderServerLimitMin = 0;
	public $nextCallVoteTimeOut = 60000;
	public $callVoteRatio = 0.5;
	public $allowMapDownload = false;
	public $autoSaveReplays = false;
	public $autoSaveValidationReplays = false;
	public $refereeMode = 0;
}

?>