<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Utils\GbxReader;

abstract class AbstractStructure
{
	abstract static function fetch($fp);
	
	final static function ignore($fp, $length)
	{
		fread($fp, $length);
	}
	
	final static function fetchLong($fp)
	{
		$long = unpack('V', fread($fp, 4));
		return $long[1];
	}
	
	final static function fetchChecksum($fp)
	{
		$checksum = unpack('H64', fread($fp, 32));
		return $checksum[1];
	}
	
	final static function fetchString($fp)
	{
		$length = self::fetchLong($fp);
		return $length ? fread($fp, $length) : '';
	}
	
	final static function fetchDate($fp)
	{
		$date = unpack('v4', fread($fp, 8));
		// create an int64 string representing the number of 100-nanoseconds since 01/01/1601 00:00:00
		$date = array_reduce(array_reverse($date), function (&$res, $value) { return bcadd($value, bcmul($res, '65536')); }, '0');
		// convert it to a number of seconds
		$date = bcdiv($date, '10000000');
		// substract the difference with EPOCH to get a Unix timestamp
		$date = bcsub($date, '11644473600');
		// return the DateTime object
		return new \DateTime('@'.$date);
	}
}

?>
