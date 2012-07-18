<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

class Server extends AbstractObject
{
	public $login;
	public $name;
	public $titleId;
	public $rpcHost;
	public $rpcPort;
	public $rpcPassword;
	public $joinIp;
	public $joinPort;
	public $joinPassword;
	public $specPassword;
	public $isRelay;
	
	function getJoinLink()
	{
		$isLan = preg_match('/_\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}_\d{1,5}/', $this->login);
		return 'maniaplanet://#join='.($isLan ? $this->joinIp : $this->login).($this->joinPassword ? ':'.$this->joinPassword : '');
	}
	
	function getSpectateLink()
	{
		$isLan = preg_match('/_\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}_\d{1,5}/', $this->login);
		return 'maniaplanet://#spectate='.($isLan ? $this->joinIp : $this->login).($this->specPassword ? ':'.$this->specPassword : '');
	}
}

?>