<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class Title extends AbstractObject
{

	/**
	 * @var string
	 */
	public $idString;
	
	/**
	 * @var string
	 */
	public $game;
	
	/**
	 * @var string
	 */
	public $environment;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string[]
	 */
	public $mapTypes = array();

	/**
	 * @var string
	 */
	public $script;

	/**
	 * @var string
	 */
	public $filename;
	
	/**
	 * @var \Maniaplanet\DedicatedServer\Structures\ScriptSettings[]
	 */
	public $scriptSettings = array();

}

?>