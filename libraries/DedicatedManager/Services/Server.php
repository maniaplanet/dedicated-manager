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
	/** @var string */
	public $login;
	/** @var string */
	public $name;
	/** @var string */
	public $titleId;
	/** @var string */
	public $rpcHost;
	/** @var int */
	public $rpcPort;
	/** @var string */
	public $rpcPassword;
	/** @var string */
	public $joinIp;
	/** @var int */
	public $joinPort;
	/** @var string */
	public $joinPassword;
	/** @var string */
	public $specPassword;
	/** @var bool */
	public $isRelay;
	
	/**
	 * @return string
	 */
	function getLink($method='join')
	{
		$isLan = preg_match('/_\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}_\d{1,5}/', $this->login);
		$password = preg_match('/^q?join$/i', $method) ? $this->joinPassword : $this->specPassword;
		return 'maniaplanet://#'.$method.'='.($isLan ? $this->joinIp : $this->login).($password ? ':'.$password : '').'@'.$this->titleId;
	}
}

?>