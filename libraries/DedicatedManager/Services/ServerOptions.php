<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

class ServerOptions extends \DedicatedApi\Structures\ServerOptions
{
	public $nextMaxPlayers = 16;
	public $nextMaxSpectators = 8;
	public $nextLadderMode = 1;
	public $ladderServerLimitMax = 50000;
	public $ladderServerLimitMin = 0;
	public $nextCallVoteTimeOut = 60000;
	public $callVoteRatio = 0.5;
	public $allowMapDownload = false;
	public $autoSaveReplays = false;
	public $autoSaveValidationReplays = false;
	public $refereeMode = 0;
	public $clientInputsMaxLatency = 0;
	public $disableHorns = false;
	
	function ensureCast()
	{
		$this->hideServer = (bool) $this->hideServer;
		$this->nextMaxPlayers = (int) $this->nextMaxPlayers;
		$this->nextMaxSpectators = (int) $this->nextMaxSpectators;
		$this->isP2PUpload = (bool) $this->isP2PUpload;
		$this->isP2PDownload = (bool) $this->isP2PDownload;
		$this->nextLadderMode = (int) $this->nextLadderMode;
		$this->nextCallVoteTimeOut = (int) $this->nextCallVoteTimeOut;
		$this->callVoteRatio = (double) $this->callVoteRatio;
		$this->allowMapDownload = (bool) $this->allowMapDownload;
		$this->autoSaveReplays = (bool) $this->autoSaveReplays;
		$this->autoSaveValidationReplays = (bool) $this->autoSaveValidationReplays;
		$this->refereeMode = (int) $this->refereeMode;
		$this->refereePassword = (int) $this->refereePassword;
		$this->clientInputsMaxLatency = (int) $this->clientInputsMaxLatency;
		$this->disableHorns = (bool) $this->disableHorns;
	}
}

?>