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

class Audio extends Music
{

	protected $xmlTagName = 'music';
	protected $posX = 0;
	protected $posY = 0;
	protected $posZ = 0;
	protected $play;
	protected $looping = 0;

	/**
	 * Autoplay the data when it's done loading
	 */
	function autoPlay()
	{
		$this->play = 1;
	}

	/**
	 * Loop when the end of the data is reached
	 */
	function enableLooping()
	{
		$this->looping = 1;
	}

	/**
	 * Returns whether auto playing is enabled
	 * @return boolean
	 */
	function getAutoPlay()
	{
		return $this->play;
	}

	/**
	 * Returns whether looping is enabled
	 * @return boolean
	 */
	function getLooping()
	{
		return $this->looping;
	}

	protected function postFilter()
	{
		parent::postFilter();
		if($this->play !== null)
			$this->xml->setAttribute('play', $this->play);
		if($this->looping !== null)
			$this->xml->setAttribute('looping', $this->looping);
	}

}

?>