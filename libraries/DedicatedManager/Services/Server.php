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
	public $rpcHost;
	public $rpcPort;
	public $rpcPassword;
	public $joinIp;
	public $joinPort;
	public $joinPassword;
	public $specPassword;
	public $isRelay;
}

?>