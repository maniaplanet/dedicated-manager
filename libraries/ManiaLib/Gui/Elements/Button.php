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

namespace ManiaLib\Gui\Elements;

class Button extends \ManiaLib\Gui\Elements\Label
{
	const CardButtonMedium = 'CardButtonMedium';
	const CardButtonMediumWide = 'CardButtonMediumWide';
	const CardButtonSmallWide = 'CardButtonSmallWide';
	const CardButtonSmall = 'CardButtonSmall';

	protected $subStyle = null;
	protected $style = self::CardButtonMedium;

	function __construct($sizeX = 35, $sizeY = 7)
	{
		parent::__construct($sizeX, $sizeY);
	}

}

?>