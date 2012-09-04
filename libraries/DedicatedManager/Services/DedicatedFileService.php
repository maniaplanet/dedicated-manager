<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

abstract class DedicatedFileService extends AbstractService
{
	/** @var string */
	protected $directory;
	/** @var string */
	protected $rootTag;
	
	/**
	 * @return string[]
	 */
	final function getList()
	{
		if(!file_exists($this->directory))
		{
			return array();
		}
		$currentDir = getcwd();
		chdir($this->directory);

		$files = array();
		foreach(glob('*.[tT][xX][tT]') as $file)
		{
			$content = file_get_contents($this->directory.$file, false, null, 0, 100);
			if(stripos($content, $this->rootTag) !== false)
			{
				$files[] = stristr($file, '.txt', true);
			}
		}
		chdir($currentDir);
		return $files;
	}

	/**
	 * @param string[] $files
	 */
	final function deleteList(array $files)
	{
		foreach($files as $file)
		{
			if(file_exists($this->directory.$file.'.txt'))
			{
				unlink($this->directory.$file.'.txt');
			}
		}
	}
	
	/**
	 * @param string $val
	 * @return bool
	 */
	static final function toBool($val)
	{
		return (strcasecmp($val, 'true') == 0 || $val == 1);
	}

	/**
	 * @param string $path
	 * @return string
	 */
	static final function securePath($path)
	{
		return realpath((stripos(PHP_OS, 'WIN') === 0 ? utf8_decode($path) : $path)).'/';
	}

}

?>