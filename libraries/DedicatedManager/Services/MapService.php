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
	/** @var string */
	protected $mapDirectory;

	function __construct()
	{
		$config = \DedicatedManager\Config::getInstance();
		$this->mapDirectory = $config->dedicatedPath.'UserData/Maps/';
	}
	
	/**
	 * @param string $filename
	 * @param string $path
	 * @return Map
	 * @throws \InvalidArgumentException
	 */
	function get($filename, $path)
	{
		if(mb_detect_encoding($filename, 'UTF-8', true) === false)
			$utf8Filename = utf8_encode($filename);
		else
			$utf8Filename = $filename;
		
		if(!file_exists($this->mapDirectory.$path.$filename))
		{
			$this->db()->execute(
					'DELETE FROM Maps WHERE path=%s AND filename=%s',
					$this->db()->quote($path),
					$this->db()->quote($utf8Filename)
				);
			throw new \InvalidArgumentException($this->mapDirectory.$path.$utf8Filename.': file does not exist');
		}
		
		$fileStats = stat($this->mapDirectory.$path.$filename);
		$result = $this->db()->execute(
				'SELECT * FROM Maps WHERE path=%s AND filename=%s AND size=%d AND mTime=FROM_UNIXTIME(%d)',
				$this->db()->quote($path),
				$this->db()->quote($utf8Filename),
				$fileStats['size'],
				$fileStats['mtime']
			);
		
		if(!$result->recordCount())
		{
			$mapInfo = \DedicatedManager\Utils\GbxReader\Map::read($this->mapDirectory.$path.$filename);
			$map = new Map();
			
			$fields = array(
				'uid', 'name', 'environment', 'mood', 'type', 'displayCost', 'nbLaps',
				'authorLogin', 'authorNick', 'authorZone',
				'authorTime', 'goldTime', 'silverTime', 'bronzeTime', 'authorScore',
				'size', 'mTime'
			);
			$this->db()->execute(
					'INSERT INTO Maps(path, filename, %s) '.
					'VALUES (%s,%s,%s,%s,%s,%s,%s,%d,%d,%s,%s,%s,%d,%d,%d,%d,%d,%d,FROM_UNIXTIME(%d)) '.
					'ON DUPLICATE KEY UPDATE '.\ManiaLib\Database\Tools::getOnDuplicateKeyUpdateValuesString($fields),
					implode(',', $fields),
					$this->db()->quote($map->path = $path),
					$this->db()->quote($map->filename = $utf8Filename),
					$this->db()->quote($map->uid = $mapInfo->uid),
					$this->db()->quote($map->name = $mapInfo->name),
					$this->db()->quote($map->environment = $mapInfo->environment),
					$this->db()->quote($map->mood = $mapInfo->mood),
					$this->db()->quote($map->type = $mapInfo->type),
					$map->displayCost = $mapInfo->displayCost,
					$map->nbLaps = $mapInfo->nbLaps,
					$this->db()->quote($map->authorLogin = $mapInfo->author->login),
					$this->db()->quote($map->authorNick = $mapInfo->author->nickname),
					$this->db()->quote($map->authorZone = $mapInfo->author->zone),
					$map->authorTime = $mapInfo->authorTime,
					$map->goldTime = $mapInfo->goldTime,
					$map->silverTime = $mapInfo->silverTime,
					$map->bronzeTime = $mapInfo->bronzeTime,
					$map->authorScore = $mapInfo->authorScore,
					$fileStats['size'],
					$fileStats['mtime']
				);
			
			if($mapInfo->thumbnail)
				imagejpeg($mapInfo->thumbnail, MANIALIB_APP_PATH.'/www/media/images/thumbnails/'.$map->uid.'.jpg', 100);
		}
		else
		{
			$map = Map::fromRecordSet($result);
		}
		
		return $map;
	}

	/**
	 * @param string $path
	 * @param bool $recursive
	 * @param bool $isLaps
	 * @param string[] $mapTypes
	 * @param string $environment
	 * @param int $offset
	 * @param int $length
	 * @return Map[]
	 */
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
			else if(preg_match('/\.map\.gbx$/ui', substr($filename, -8)))
			{
				try
				{
					$file = $this->get($filename, $path);
					if( (!$isLaps || ($isLaps && $file->nbLaps != 0))
							&& (!$environment || ($environment && $environment == $file->environment))
							&& (!$mapTypes || $mapTypes && in_array(strtolower($file->type), $mapTypes, true)) )
					{
						$maps[] = $file;
					}
				}
				catch(\Exception $e)
				{
					\ManiaLib\Utils\Logger::user('Cannot parse `'.$path.$filename.'`');
				}
			}
		}
		
		usort($maps, array(__CLASS__, 'compareFiles'));
		
		if($offset !== null)
		{
			return array_slice($maps, $offset, $length);
		}

		return $maps;
	}
	
	/**
	 * @param string[] $maps
	 */
	function delete(array $maps)
	{
		foreach($maps as $map)
		{
			if(file_exists($this->mapDirectory.$map))
			{
				unlink($this->mapDirectory.$map);
			}
			$this->db()->execute(
					'DELETE FROM Maps WHERE path=%s AND filename=%s',
					$this->db()->quote(dirname($map).'/'),
					$this->db()->quote(basename($map))
				);
		}
	}
	
	/**
	 * @param string $tmpFile
	 * @param string $filename
	 * @param string $path
	 * @throws \Exception
	 */
	function upload($tmpFile, $filename, $path)
	{
		if(!move_uploaded_file($tmpFile, $this->mapDirectory.$path.$filename))
		{
			throw new \Exception();
		}
	}

	/**
	 * @param File $a
	 * @param File $b
	 * @return int
	 */
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
