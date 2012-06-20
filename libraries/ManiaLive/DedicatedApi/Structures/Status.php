<?php
/**
 * Represents the status of a Dedicated TrackMania Server
 * ManiaLive - TrackMania dedicated server manager in PHP
 *
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace ManiaLive\DedicatedApi\Structures;

class Status extends AbstractStructure
{
	const UNKNOWN         = 0;
	const WAITING         = 1;
	const LAUNCHING       = 2;
	const SYNCHRONIZATION = 3;
	const PLAY            = 4;
	const EXITING         = 6;

	public $code;
	public $name;
}