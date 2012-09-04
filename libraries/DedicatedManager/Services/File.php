<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class File extends AbstractObject
{
	/** @var string */
	public $filename;
	/** @var string */
	public $path;
	/** @var bool */
	public $isDirectory = false;
}

?>