<?php

/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Services;

class Map extends File
{
	public $isDirectory = false;
	public $uid;
	public $name;
	public $environment;
	public $mood;
	public $type;
	public $displayCost;
	public $nbLaps;
	
	public $authorLogin;
	public $authorNick;
	public $authorZone;
	
	public $authorTime;
	public $goldTime;
	public $silverTime;
	public $bronzeTime;
	public $authorScore;
}

?>