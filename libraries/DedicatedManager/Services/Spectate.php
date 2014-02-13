<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class Spectate extends \Maniaplanet\DedicatedServer\Structures\AbstractStructure
{
	/** @var string */
	public $method = 'managed';
	/** @var string */
	public $managed = '';
	/** @var string */
	public $login = '';
	/** @var string */
	public $ip = '127.0.0.1';
	/** @var int */
	public $port = 2350;
	/** @var string */
	public $password = '';
}

?>
