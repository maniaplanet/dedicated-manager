<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class MapService extends AbstractService
{
	protected $mapDirectory;
	protected $cache;

	function __construct()
	{
		$config = \DedicatedManager\Config::getInstance();
		$this->mapDirectory = $config->dedicatedPath.'UserData/Maps/';
		$this->cache = \ManiaLib\Cache\Cache::factory(\ManiaLib\Cache\MYSQL);
	}

	function getData($filename, $path)
	{
		if(!file_exists($this->mapDirectory.$path.$filename))
		{
			$this->cache->delete($path.$filename);
			throw new \InvalidArgumentException($this->mapDirectory.$path.$filename.' : file does not exist');
		}
		$map = $this->cache->fetch($path.$filename);
		if($map === false)
		{
			$file = file_get_contents($this->mapDirectory.$path.$filename, null, null, 0, 0x10000);

			if($file === false || (stristr($file, '<header type="map"') === false && stristr($file, '<header type="challenge"') === false))
			{
				throw new \InvalidArgumentException('file is not a map');
			}

			$search = strstr($file, '<header');
			$header = strstr($search, '</header>', true).'</header>';

			$header = simplexml_load_string($header);

			$map = new Map();
			$map->filename = $filename;
			$map->path = $path;
			$map->uid = (string) $header->ident->attributes()->uid;
			$map->name = (string) $header->ident->attributes()->name;
			$map->author = (string) $header->ident->attributes()->author;
			$map->environment = (string) $header->desc->attributes()->envir;
			$map->mood = (string) $header->desc->attributes()->mood;
			$map->type = (string) $header->desc->attributes()->type;
			if($map->type == 'Script')
			{
				$map->type = (string) $header->desc->attributes()->maptype;
			}
			$map->nbLaps = (int) $header->desc->attributes()->nblaps;
			$map->goldTime = (int) $header->times->attributes()->gold;

			try
			{
				$thumbnailBinary = substr(stristr(stristr($file, '<Thumbnail.jpg>'), '</Thumbnail.jpg>', true), strlen('<Thumbnail.jpg>'));
				$mirroredThumbnail = imagecreatefromstring($thumbnailBinary);
				$width = imagesx($mirroredThumbnail);
				$height = imagesy($mirroredThumbnail);
				$thumbnail = imagecreatetruecolor($width, $height);
				foreach(range($height - 1, 0) as $oldY => $newY)
					imagecopy($thumbnail, $mirroredThumbnail, 0, $newY, 0, $oldY, $width, 1);
				imagejpeg($thumbnail, MANIALIB_APP_PATH.'/www/media/images/thumbnails/'.$map->uid.'.jpg', 100);
			}
			catch(\Exception $e)
			{

			}
			$this->cache->add($path.$filename, $map, 7200 + rand(-600, 600));
		}
		return $map;
	}

	function getList($path, $recursive = false, $isLaps = false, array $mapTypes = array(), $environment = '', $offset = null, $length = null)
	{
		$workPath = DedicatedFileService::securePath($this->mapDirectory.$path);
		$mapTypes = array_map('strtolower', $mapTypes);
		$maps = array();

		$path = str_ireplace('\\', '/', $path);
		$files = scandir($workPath);
		foreach($files as $filename)
		{
			if(is_dir($workPath.'/'.$filename))
			{
				if($filename == '.' || $filename == '..')
					continue;
				
				$file = new Directory();
				$file->filename = $filename;
				$file->path = $path;
				if($recursive)
				{
					$file->children = $this->getList($path.$filename.'/', $recursive, $isLaps, $mapTypes, $environment);
					if($file->children)
					{
						$maps[] = $file;
					}
				}
				else
				{
					$maps[] = $file;
				}
			}
			else if(preg_match('/\.map\.gbx$/ui', $filename))
			{
				try
				{
					$file = $this->getData($filename, $path);
					if( (!$isLaps || ($isLaps && $file->nbLaps != 0))
							&& (!$environment || ($environment && $environment == $file->environment))
							&& (!$mapTypes || $mapTypes && in_array(strtolower($file->type), $mapTypes, true)) )
					{
						$maps[] = $file;
					}
				}
				catch(\InvalidArgumentException $e){}
			}
		}
		
		usort($maps, array(__CLASS__, 'compareFiles'));
		
		if($offset !== null)
		{
			return array_slice($maps, $offset, $length);
		}

		return $maps;
	}

	static final function compareFiles(File $a, File $b)
	{
		$order = $a->isDirectory - $b->isDirectory;
		if(!$order)
		{
			$order = strcmp($a->filename, $b->filename);
		}
		return $order;
	}
}

?>
