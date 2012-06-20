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

namespace ManiaLib\Gui\Cards\Dialogs;

class TwoButtons extends OneButton
{

	/**
	 * @var \ManiaLib\Gui\Elements\Button
	 */
	public $button2;

	function __construct($sizeX = 65, $sizeY = 25)
	{
		parent::__construct($sizeX, $sizeY);
		$this->titleBg->setSubStyle(\ManiaLib\Gui\Elements\Bgs1::BgTitle3_1);

		$this->button->setPosition(-15, 0, 0);

		$this->button2 = new \ManiaLib\Gui\Elements\Button;
		$this->button2->setPosition(15, 0, 0);
		$this->button2->setAlign('left', 'bottom');
		$this->addCardElement($this->button2);
	}

	function preFilter()
	{
		parent::preFilter();
		$this->button->setHalign('right');
		$this->button2->setPositionY(5 - $this->sizeY);
		$this->button->setPositionX(-5);
		$this->button2->setPositionX(5);
	}

}

?>