<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class ManialiveService extends AbstractService
{
	/**
	 * @return string[]
	 */
	function getPlugins()
	{
		$manialivePath = \DedicatedManager\Config::getInstance()->manialivePath;
		require $manialivePath.'libraries/autoload.php';

		$availablePlugins = array();

		foreach($this->searchFolderForPlugin($manialivePath.'libraries/ManiaLivePlugins') as $plugin)
			if(($class = $this->validatePlugin($plugin)) !== false)
				$availablePlugins[] = $class;

		return $availablePlugins;
	}

	/**
	 * @param string $configFile
	 * @param mixed[] $options
	 * @return int
	 * @throws \Exception
	 */
	function start($configFile, array $options = array())
	{
		$config = \DedicatedManager\Config::getInstance();
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			$startCommand = 'START php.exe bootstrapper.php';
		else
			$startCommand = 'php bootstrapper.php';
		$startCommand .= ' --manialive_cfg='.escapeshellarg($configFile.'.ini');
		
		foreach($options as $key => $value)
			$startCommand .= ' --'.$key.'='.escapeshellarg($value);
		
		if(!$isWindows)
			$startCommand .= ' &';
		
		// Getting current PIDs
		$currentPids = $this->getPIDs();
		
		// Starting dedicated
		$procHandle = proc_open($startCommand, array(), $pipes, $config->manialivePath);
		proc_close($procHandle);

		// Getting its PID
		$diffPids = array_diff($this->getPIDs(), $currentPids);
		if(!$diffPids)
			throw new \Exception('Can\'t start dedicated server.');
		return reset($diffPids);
	}
	
	/**
	 * @param int $pid
	 */
	function stop($pid)
	{
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows)
			`TASKKILL /PID $pid`;
		else
			`kill -9 $pid`;
	}
	
	/**
	 * @param string $plugin
	 * @return string|bool
	 */
	private function validatePlugin($plugin)
	{
		$matches = array();
		if(preg_match('/(ManiaLivePlugins.*)\.php/', $plugin, $matches))
		{
			$class = '\\'.str_replace('/', '\\', $matches[1]);
			if(class_exists($class) && is_subclass_of($class, '\ManiaLive\PluginHandler\Plugin'))
				return implode('\\', array_slice(explode('\\', $class), 2, 2));
			else
				return false;
		}
	}

	/**
	 * @param string $folder
	 * @return string[]
	 */
	private function searchFolderForPlugin($folder)
	{
		$plugins = array();

		$path = explode(DIRECTORY_SEPARATOR, $folder);
		$parent = end($path);

		foreach(scandir($folder) as $file)
		{
			if($file == '.' || $file == '..')
				continue;

			$filePath = $folder.DIRECTORY_SEPARATOR.$file;

			//If it's a directory digg deeper
			if(is_dir($filePath))
				$plugins = array_merge($plugins, $this->searchFolderForPlugin($filePath));
			else
			{
				$pathParts = pathinfo($filePath);
				//If the file got the name of the parent folder or is called Plugin it should be a Plugin
				if($pathParts['filename'] == $parent || $pathParts['filename'] == 'Plugin')
					$plugins[] = $filePath;
			}
		}

		return $plugins;
	}

	/**
	 * @return int[]
	 */
	private function getPIDs()
	{
		if(stripos(PHP_OS, 'WIN') === 0)
		{
			$dedicatedProc = `TASKLIST /FI "IMAGENAME eq php.exe" /NH`;
			if(preg_match_all('/php\.exe\s+(\d+)/m', $dedicatedProc, $matches))
				return $matches[1];
		}
		else
		{
			$dedicatedProc = `ps -C "php" --format pid --no-headers --sort +cputime`;
			if(preg_match_all('/(\\d+)/', $dedicatedProc, $matches))
				return $matches[1];
		}
		return array();
	}
}

?>