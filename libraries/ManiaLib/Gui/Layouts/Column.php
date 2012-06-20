<?php
/**
 * ManiaLib - Lightweight PHP framework for Manialinks
 * 
 * @see         http://code.google.com/p/manialib/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace ManiaLib\Gui\Layouts;

/**
 * Elements are added below their predecessor
 */
class Column extends AbstractLayout
{
	const DIRECTION_DOWN = -1;
	const DIRECTION_UP = 1;

	protected $direction;

	function __construct($sizeX = 20, $sizeY = 20,
		$direction = self::DIRECTION_DOWN)
	{
		parent::__construct($sizeX, $sizeY);
		$this->direction = $direction;
	}

	function setDirection($direction)
	{
		$this->direction = $direction;
	}

	function preFilter(\ManiaLib\Gui\Component $item)
	{
		if($this->direction == self::DIRECTION_UP)
		{
			$this->yIndex += $item->getRealSizeY() + $this->marginHeight;
		}
	}

	function postFilter(\ManiaLib\Gui\Component $item)
	{
		if($this->direction == self::DIRECTION_DOWN)
		{
			$this->yIndex -= $item->getRealSizeY() + $this->marginHeight;
		}
	}

}

?>