<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class TitleService extends AbstractService
{

	protected $titleList = array();

	function __construct()
	{
		$title = new Title();
		$title->idString = 'SMStormEliteExperimental@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Elite (experimental)';
		$title->script = 'Elite.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Elite (experimental).Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormEliteExperimental@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'SMStormElite@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Elite';
		$title->script = 'Elite.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Elite.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormElite@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'SMStormHeroes@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Heroes';
		$title->script = 'Heroes.Script.txt';
		$title->mapTypes = array('EliteArena', 'HeroesArena');
		$title->filename = 'Heroes.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormHeroes@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'SMStormJoust@nadeolabs';
		$title->game = 'ShootMania';
		$title->name = 'ShootMania Storm Joust';
		$title->script = 'Joust.Script.txt';
		$title->mapTypes[] = 'JoustArena';
		$title->filename = 'Joust.Title.Pack.Gbx';
		$title->environment = 'Storm';
		$this->titleList['SMStormJoust@nadeolabs'] = $title;

		$title = new Title();
		$title->idString = 'Platform@nadeolive';
		$title->game = 'TrackMania';
		$title->name = 'TrackMania Canyon Platform';
		$title->script = 'PlatformMulti.Script.txt';
		$title->mapTypes[] = 'Platform';
		$title->filename = 'Platform.Title.Pack.Gbx';
		$title->environment = 'Canyon';
		$this->titleList['Platform@nadeolive'] = $title;
	}

	function getList()
	{
		return $this->titleList;
	}

	function isCustomTitle($idString)
	{
		return array_key_exists($idString, $this->titleList);
	}

	function getMapTypes($idString)
	{
		if(!$this->isCustomTitle($idString)) throw new \InvalidArgumentException();
		
		$game = $this->titleList[$idString]->game;
		$mapTypes = $this->titleList[$idString]->mapTypes;
		foreach($mapTypes as $mapType)
		{
			$mapTypes[] = $game.'\\'.$mapType;
		}
		
		return $mapTypes;
	}
	
	function getScript($idString)
	{
		if(!$this->isCustomTitle($idString)) throw new \InvalidArgumentException();
		
		return $this->titleList[$idString]->script;
	}

}

?>