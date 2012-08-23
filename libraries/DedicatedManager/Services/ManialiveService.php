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

	function getPlugins()
	{
		$manialivePath = \DedicatedManager\Config::getInstance()->manialivePath;
		require $manialivePath.'libraries/autoload.php';

		$availablePlugins = array();

		foreach($this->searchFolderForPlugin($manialivePath.'libraries/ManiaLivePlugins') as $plugin)
			if(($class = $this->validatePlugin($plugin)) !== false) $availablePlugins[] = $class;

		return $availablePlugins;
	}

	function start($configFile, array $options = array())
	{
		$config = \DedicatedManager\Config::getInstance();
		$isWindows = stripos(PHP_OS, 'WIN') === 0;
		if($isWindows) $startCommand = 'START php.exe "'.$config->manialivePath.'bootstrapper.php"';
		else $startCommand = 'cd "'.$config->manialivePath.'"; php bootstrapper.php';
		$startCommand .= ' --manialive_cfg='.escapeshellarg($configFile);
		$cmd = array();
		foreach($options as $key => $value)
		{
			$cmd[] = '--'.$key.'='.escapeshellarg($value);
		}
		$startCommand .= ' '.implode(' ', $cmd);
		$procHandle = proc_open($startCommand, array(), $pipes);
		sleep(3);
		$status = proc_get_status($procHandle);
		proc_close($procHandle);
		return $status['pid'];
	}

	function register($host, $port, $pid)
	{
		$this->db()->execute('UPDATE Servers SET manialivePID = %d WHERE rpcHost = %s AND rpcPort = %d', $pid,
			$this->db()->quote($host), $port);
	}

	private function validatePlugin($plugin)
	{
		$matches = array();
		if(preg_match('/(ManiaLivePlugins.*)\.php/', $plugin, $matches))
		{
			$class = '\\'.str_replace('/', '\\', $matches[1]);
			if(class_exists($class) && is_subclass_of($class, '\ManiaLive\PluginHandler\Plugin'))
					return implode('\\', array_slice(explode('\\', $class), 2, 2));
			else return false;
		}
	}

	private function searchFolderForPlugin($folder)
	{
		$plugins = array();

		$path = explode(DIRECTORY_SEPARATOR, $folder);
		$parent = end($path);

		foreach(scandir($folder) as $file)
		{
			if($file == '.' || $file == '..') continue;

			$filePath = $folder.DIRECTORY_SEPARATOR.$file;

			//If it's a directory digg deeper
			if(is_dir($filePath)) $plugins = array_merge($plugins, $this->searchFolderForPlugin($filePath));
			else
			{
				$pathParts = pathinfo($filePath);
				//If the file got the name of the parent folder or is called Plugin it should be a Plugin
				if($pathParts['filename'] == $parent || $pathParts['filename'] == 'Plugin') $plugins[] = $filePath;
			}
		}

		return $plugins;
	}

}

?>