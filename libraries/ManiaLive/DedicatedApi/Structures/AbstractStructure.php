<?php
/**
 * ManiaLive - TrackMania dedicated server manager in PHP
 * 
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace ManiaLive\DedicatedApi\Structures;

abstract class AbstractStructure
{
	static public function fromArray($array)
	{
		if(!is_array($array))
			return $array;
		
		$object = new static;
		foreach($array as $key => $value)
			$object->{lcfirst($key)} = $value;
		return $object;
	}
	
	static public function fromArrayOfArray($array)
	{
		if(!is_array($array))
			return $array;
		
		$result = array();
		foreach($array as $key => $value)
			$result[$key] = static::fromArray($value);
		return $result;
	}
	
	static public function getPropertyFromArray($array, $property)
	{
		return array_map(get_called_class().'::extractProperty', $array, array_fill(0, count($array), $property));
	}
	
	static protected function extractProperty($element, $property)
	{
		if(!is_a($element, get_called_class()) || !property_exists($element, $property))
		throw new \InvalidArgumentException('property '.$property.' does not exists in class: '.get_called_class());
		
		return $element->$property;
	}
	
	function toArray()
	{
		$out = array();
		foreach(get_object_vars($this) as $key => $value)
			$out[ucfirst($key)] = $value;
		return $out;
	}
}

?>