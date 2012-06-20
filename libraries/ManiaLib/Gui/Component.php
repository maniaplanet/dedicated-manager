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

namespace ManiaLib\Gui;

abstract class Component
{

	protected $id;
	protected $posX = 0;
	protected $posY = 0;
	protected $posZ = 0;
	protected $sizeX;
	protected $sizeY;
	protected $scale;
	protected $visible = true;
	protected $valign = null;
	protected $halign = null;
	protected $scriptEvents;

	/**
	 * Set the id of the element
	 * @param int
	 */
	function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Sets the X position of the element
	 * @param float
	 */
	function setPositionX($posX)
	{
		$oldX = $this->posX;
		$this->posX = $posX;
		$this->onMove($oldX, $this->posY, $this->posZ);
	}

	/**
	 * Sets the Y position of the element
	 * @param float
	 */
	function setPositionY($posY)
	{
		$oldY = $this->posY;
		$this->posY = $posY;
		$this->onMove($this->posX, $oldY, $this->posZ);
	}

	/**
	 * Sets the Z position of the element
	 * @param float
	 */
	function setPositionZ($posZ)
	{
		$oldZ = $this->posZ;
		$this->posZ = $posZ;
		$this->onMove($this->posX, $this->posY, $oldZ);
	}

	/**
	 * Sets the X position of the element
	 * @param float
	 */
	function setPosX($posX)
	{
		$oldX = $this->posX;
		$this->posX = $posX;
		$this->onMove($oldX, $this->posY, $this->posZ);
	}

	/**
	 * Sets the Y position of the element
	 * @param float
	 */
	function setPosY($posY)
	{
		$oldY = $this->posY;
		$this->posY = $posY;
		$this->onMove($this->posX, $oldY, $this->posZ);
	}

	/**
	 * Sets the Z position of the element
	 * @param float
	 */
	function setPosZ($posZ)
	{
		$oldZ = $this->posZ;
		$this->posZ = $posZ;
		$this->onMove($this->posX, $this->posY, $oldZ);
	}

	/**
	 * Increment position X
	 * @param float
	 */
	function incPosX($posX)
	{
		$oldX = $this->posX;
		$this->posX += $posX;
		$this->onMove($oldX, $this->posY, $this->posZ);
	}

	/**
	 * Increment position Y
	 * @param float
	 */
	function incPosY($posY)
	{
		$oldY = $this->posY;
		$this->posY += $posY;
		$this->onMove($this->posX, $oldY, $this->posZ);
	}

	/**
	 * Increment position Z
	 * @param float
	 */
	function incPosZ($posZ)
	{
		$oldZ = $this->posZ;
		$this->posZ += $posZ;
		$this->onMove($this->posX, $this->posY, $oldZ);
	}

	/**
	 * Sets the position of the element
	 * @param float
	 * @param float
	 * @param float
	 */
	function setPosition()
	{
		$oldX = $this->posX;
		$oldY = $this->posY;
		$oldZ = $this->posZ;

		$args = func_get_args();

		if(!empty($args))
			$this->posX = array_shift($args);

		if(!empty($args))
			$this->posY = array_shift($args);

		if(!empty($args))
			$this->posZ = array_shift($args);

		$this->onMove($oldX, $oldY, $oldZ);
	}

	/**
	 * Sets the vertical alignment of the element.
	 * @param string Vertical alignment can be either "top", "center" or
	 * "bottom"
	 */
	function setValign($valign)
	{
		$old = $this->valign;
		$this->valign = $valign;
		$this->onAlign($this->halign, $old);
	}

	/**
	 * Sets the horizontal alignment of the element
	 * @param string Horizontal alignement can be eithe "left", "center" or
	 * "right"
	 */
	function setHalign($halign)
	{
		$old = $this->halign;
		$this->halign = $halign;
		$this->onAlign($old, $this->valign);
	}

	/**
	 * Sets the alignment of the element
	 * @param string Horizontal alignement can be eithe "left", "center" or
	 * "right"
	 * @param string Vertical alignment can be either "top", "center" or
	 * "bottom"
	 */
	function setAlign($halign = null, $valign = null)
	{
		$oldHalign = $this->halign;
		$oldValign = $this->valign;
		$this->valign = $valign;
		$this->halign = $halign;
		$this->onAlign($oldHalign, $oldValign);
	}

	/**
	 * Sets the width of the element
	 * @param float
	 */
	function setSizeX($sizeX)
	{
		$oldX = $this->sizeX;
		$this->sizeX = $sizeX;
		$this->onResize($oldX, $this->sizeY);
	}

	/**
	 * Sets the height of the element
	 * @param float
	 */
	function setSizeY($sizeY)
	{
		$oldY = $this->sizeY;
		$this->sizeY = $sizeY;
		$this->onResize($this->sizeX, $oldY);
	}

	/**
	 * Sets the size of the element
	 * @param float
	 * @param float
	 */
	function setSize()
	{
		$oldX = $this->sizeX;
		$oldY = $this->sizeY;

		$args = func_get_args();

		if(!empty($args))
			$this->sizeX = array_shift($args);

		if(!empty($args))
			$this->sizeY = array_shift($args);

		$this->onResize($oldX, $oldY);
	}

	/**
	 * Sets the scale factor of the element. 1=original size, 2=double size, 0.5
	 * =half size
	 * @param float
	 */
	function setScale($scale)
	{
		$oldScale = $this->scale;
		$this->scale = $scale;
		$this->onScale($oldScale);
	}

	/**
	 * Sets the visibility of the Component.
	 * This is used by ManiaLive.
	 * @param bool $visible If set to false the Component (and subcomponents) is not rendered.
	 */
	function setVisibility($visible)
	{
		$this->visible = $visible;
	}

	/**
	 * Sets additional ManiaScript events to be generated for this element.
	 * @param string
	 */
	function setScriptEvents($scriptEvent)
	{
		$this->scriptEvents = $scriptEvent;
	}

	function getId()
	{
		return $this->id;
	}

	/**
	 * Returns the X position of the element
	 * @return float
	 */
	function getPosX()
	{
		return $this->posX;
	}

	/**
	 * Returns the Y position of the element
	 * @return float
	 */
	function getPosY()
	{
		return $this->posY;
	}

	/**
	 * Returns the Z position of the element
	 * @return float
	 */
	function getPosZ()
	{
		return $this->posZ;
	}

	/**
	 * Returns the width of the element
	 * @return float
	 */
	function getSizeX()
	{
		return $this->sizeX;
	}

	/**
	 * Returns the height of the element
	 * @return float
	 */
	function getSizeY()
	{
		return $this->sizeY;
	}

	/**
	 * Returns the scale of the element
	 * @return float
	 */
	function getScale()
	{
		return $this->scale;
	}

	/**
	 * Returns the width of the element with the
	 * applied scaling factor.
	 * @return float
	 */
	function getRealSizeX()
	{
		return $this->sizeX * ($this->scale ? $this->scale : 1);
	}

	/**
	 * Returns the height of the element with the
	 * applied scaling factor.
	 * @return float
	 */
	function getRealSizeY()
	{
		return $this->sizeY * ($this->scale ? $this->scale : 1);
	}

	/**
	 * Return the x-coordinate for the left border of the Component.
	 * @return float
	 */
	function getBorderLeft()
	{
		return $this->getPosX();
	}

	/**
	 * Return the x-coordinate for the right border of the Component.
	 * @return float
	 */
	function getBorderRight()
	{
		return $this->getPosX() + $this->getRealSizeX();
	}

	/**
	 * Return y-coordinate for the top border of the Component.
	 * @return float
	 */
	function getBorderTop()
	{
		return $this->getPosY();
	}

	/**
	 * Return y-coordinate for the bottom border of the Component.
	 * @return float
	 */
	function getBorderBottom()
	{
		return $this->getPosY() + $this->getRealSizeY();
	}

	/**
	 * Returns the horizontal alignment of the element
	 * @return string
	 */
	function getHalign()
	{
		return $this->halign;
	}

	/**
	 * Returns the vertical alignment of the element
	 * @return string
	 */
	function getValign()
	{
		return $this->valign;
	}

	/**
	 * Is the Component rendered onto the screen or not?
	 * This is used by ManiaLive.
	 * @return bool
	 */
	function isVisible()
	{
		return $this->visible;
	}

	function getScriptEvents()
	{
		return $this->scriptEvents;
	}

	/**
	 * Overridable callback on component change
	 */
	protected function onResize($oldX, $oldY)
	{
		
	}

	/**
	 * Overridable callback on component change
	 */
	protected function onMove($oldX, $oldY, $oldZ)
	{
		
	}
	
	/**
	 * Overridable callback on component change
	 */
	protected function onScale($oldScale)
	{
		
	}
	
	/**
	 * Overridable callback on component change
	 */
	protected function onAlign($oldHalign, $oldValign)
	{
		
	}

}

?>