<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Helpers;

abstract class Input
{
	static function text($name, $id, $value)
	{
		return sprintf('<input type="text" name="%s" id="%s" value="%s"/>', $name, $id, htmlentities($value, ENT_QUOTES, 'utf-8'));
	}
}

?>
