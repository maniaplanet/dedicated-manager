<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Services;

class ManialiveFileService extends AbstractService
{
	/** @var string */
	protected $directory;
	
	function __construct()
	{
		$this->directory = \DedicatedManager\Config::getInstance()->manialivePath.'/config/';
	}
	
	/**
	 * @return string[]
	 */
	function getList()
	{
		if(!file_exists($this->directory))
		{
			return array();
		}
		$currentDir = getcwd();
		chdir($this->directory);

		$files = array();
		foreach(glob('*.[iI][nN][iI]') as $file)
		{
			$files[] = stristr($file, '.ini', true);
		}
		chdir($currentDir);
		return $files;
	}
	
	/**
	 * @param string[] $files
	 */
	function deleteList(array $files)
	{
		foreach($files as $file)
		{
			if(file_exists($this->directory.$file.'.ini'))
			{
				unlink($this->directory.$file.'.ini');
			}
		}
	}
	
	/**
	 * @param string $filename
	 * @return ManialiveConfig
	 * @throws \InvalidArgumentException
	 */
	function get($filename)
	{
		if(!file_exists($this->directory.$filename.'.ini'))
		{
			throw new \InvalidArgumentException('File does not exists');
		}
		
		$config = new ManialiveConfig();
		
		$content = file_get_contents($this->directory.$filename.'.ini');
		$content = preg_replace('/^\s*\[.+/msu', '', $content);
		$content = preg_replace('/^\s*;.*$\n?/mu', '', $content);
		$content = preg_replace('/;.+$/mu', '', $content);
		$content = preg_replace('/^$\n/mu', '', $content);
		$assoc = parse_ini_string($content, true);
		foreach($assoc as $key => $value)
		{
			$property = explode('.', $key, 2);
			if(count($property) < 2)
				continue;
			if($property[0] == 'manialive')
			{
				if(isset($config->{$property[1]}))
					$config->{$property[1]} = $value;
			}
			elseif(isset($config->{$property[0]}) && isset($config->{$property[0]}->{$property[1]}))
				$config->{$property[0]}->{$property[1]} = $value;
		}
		
		$config->__other = preg_replace('/^\s*(?:config|server|manialive|database|threading|wsapi)\..+$\n?/mu', '', $content);
		
		return $config;
	}
	
	/**
	 * @param string $filename
	 * @param ManialiveConfig $config
	 */
	function save($filename, ManialiveConfig $config)
	{
		$f = fopen($this->directory.$filename.'.ini', 'w');

		if($config->config->logsPath)
			fprintf($f, "config.logsPath = '%s'\n", $config->config->logsPath);
		fprintf($f, "config.logsPrefix = '%s'\n", $config->config->logsPrefix);
		fprintf($f, "config.runtimeLog = %s\n", $config->config->runtimeLog ? 'On' : 'Off');
		fprintf($f, "config.globalErrorLog = %s\n\n", $config->config->globalErrorLog ? 'On' : 'Off');
		
		foreach($config->admins as $admin)
			fprintf($f, "manialive.admins[] = '%s'\n", $admin);
		foreach($config->plugins as $plugin)
			fprintf($f, "manialive.plugins[] = '%s'\n", $plugin);
		
		fprintf($f, "\ndatabase.enable = %s\n", $config->database->enable ? 'On' : 'Off');
		fprintf($f, "database.host = '%s'\n", $config->database->host);
		fprintf($f, "database.port = %d\n", $config->database->port);
		fprintf($f, "database.username = '%s'\n", $config->database->username);
		fprintf($f, "database.password = '%s'\n", $config->database->password);
		fprintf($f, "database.database = '%s'\n\n", $config->database->database);
		
		fprintf($f, "threading.enabled = %s\n", $config->threading->enabled ? 'On' : 'Off');
		fprintf($f, "threading.busyTimeout = %d\n", $config->threading->busyTimeout);
		fprintf($f, "threading.maxTries = %d\n\n", $config->threading->maxTries);
		
		fprintf($f, "wsapi.username = '%s'\n", $config->wsapi->username);
		fprintf($f, "wsapi.password = '%s'\n\n", $config->wsapi->password);
		
		fwrite($f, $config->__other);
		
		fclose($f);
	}
}

?>
