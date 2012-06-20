<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Utils\GbxReader;

class Pack extends AbstractStructure
{
	public $version;
	public $author;
	public $manialink;
	public $creationDate;
	public $comment;
	public $directory;
	public $creationBuildInfo;
	public $includedPacks = array();
	public $checksum;
	public $flags;
	
	final static function read($filename)
	{
		$fp = fopen($filename, 'rb');
		$pack = self::fetch($fp);
		fclose($fp);
		
		return $pack;
	}

	static function fetch($fp)
	{
		$pack = new self;
		self::ignore($fp, 8);
		$pack->version = self::fetchLong($fp);
		$pack->checksum = self::fetchChecksum($fp);
		$pack->flags = self::fetchLong($fp);
		$pack->author = Author::fetch($fp);
		$pack->manialink = self::fetchString($fp);
		$pack->creationDate = self::fetchDate($fp);
		$pack->comment = self::fetchString($fp);
		$pack->directory = self::fetchString($fp);
		$pack->creationBuildInfo = self::fetchString($fp);
		self::ignore($fp, 16);
		
		$nbIncludedPacks = self::fetchLong($fp);
		for(; $nbIncludedPacks > 0; --$nbIncludedPacks)
		{
			$pack->includedPacks[] = IncludedPack::fetch($fp);
		}
		
		return $pack;
	}
}

?>
