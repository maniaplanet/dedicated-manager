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
	/** @var bool */
	public $isDirectory = false;
	/** @var string */
	public $uid;
	/** @var string */
	public $name;
	/** @var string */
	public $environment;
	/** @var string */
	public $mood;
	/** @var string */
	public $type;
	/** @var int */
	public $displayCost;
	/** @var int */
	public $nbLaps;
	
	/** @var string */
	public $authorLogin;
	/** @var string */
	public $authorNick;
	/** @var string */
	public $authorZone;
	
	/** @var int */
	public $authorTime;
	/** @var int */
	public $goldTime;
	/** @var int */
	public $silverTime;
	/** @var int */
	public $bronzeTime;
	/** @var int */
	public $authorScore;
}

?>