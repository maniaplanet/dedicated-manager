<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager;

class Config extends \ManiaLib\Utils\Singleton
{
	public $dedicatedPath = '';
	public $manialivePath = '';
	public $lanMode = false;
	public $admins = array();
}

?>