<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class Spectate extends \DedicatedApi\Structures\AbstractStructure
{
	public $method = 'managed';
	public $managed = '';
	public $login = '';
	public $ip = '127.0.0.1';
	public $port = 2350;
	public $password = '';
}

?>
