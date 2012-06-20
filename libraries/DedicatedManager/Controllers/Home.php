<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class Home extends \ManiaLib\Application\Controller
{

	protected function onConstruct()
	{
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftLink = null;
	}
	
	function index()
	{
		$config = \DedicatedManager\Config::getInstance();
		/**
		 * @var $config \DedicatedManager\Config
		 */
		$errors = array();
		$writables[] = $config->dedicatedPath;
		$currentDir = getcwd();
		if(file_exists($config->dedicatedPath.'UserData/Maps/MatchSettings/'))
		{
			$writables[] = $config->dedicatedPath.'UserData/Maps/MatchSettings/';
			chdir($config->dedicatedPath.'UserData/Maps/MatchSettings/');
			$tmp = glob('*.txt');
			$tmp = array_map(function ($a) use ($config)
					{
						return $config->dedicatedPath.'UserData/Maps/MatchSettings/'.$a;
					}, $tmp);
			$writables = array_merge($writables, $tmp);
		}
		$writables[] = $config->dedicatedPath.'UserData/Config/';
		$writables[] = $config->dedicatedPath.'Logs/';
		if(file_exists($config->dedicatedPath.'UserData/Config/'))
		{
			chdir($config->dedicatedPath.'UserData/Config/');
			$tmp = glob('*.txt');
			$tmp = array_map(function ($a) use ($config)
					{
						return $config->dedicatedPath.'UserData/Config/'.$a;
					}, $tmp);
			$writables = array_merge($writables, $tmp);
		}
		$writables[] = $config->manialivePath.'logs/';
		$writables[] = $config->manialivePath.'data/';
		chdir($currentDir);

		$executables = array();
		if(stripos(PHP_OS, 'win') !== false)
		{
			$executables[] = $config->dedicatedPath.'ManiaPlanetServer.exe';
		}
		else
		{
			$executables[] = $config->dedicatedPath.'ManiaPlanetServer';
		}

		$failed = array();
		foreach($writables as $writable)
		{
			if(file_exists($writable) && !is_writable($writable)) $failed[] = $writable;
		}
		if($failed)
		{
			$str = _('The following folders cannot be written by Apache user.').
					' '._('Contact the admin to check this.').'<br/>'.
					_('Folder list: ').'<ul>';
			foreach($failed as $fail)
			{
				$str.= '<li>'.$fail.'</li>';
			}
			$errors[] = $str.'</ul>';
		}

		$failed = array();
		foreach($executables as $executable)
		{
			if(file_exists($writable) && !is_executable($executable)) $failed[] = $executable;
		}
		if($failed)
		{
			$str = _('The following files cannot be executed by Apache user.').
					' '._('Contact the admin to check this.').'<br/>'.
					_('File list: ').'<ul>';
			foreach($failed as $fail)
			{
				$str.= '<li>'.$fail.'</li>';
			}
			$errors[] = $str.'</ul>';
		}

		if($errors) $this->session->set('error', $errors);

		$service = new \DedicatedManager\Services\ServerService();
		$servers = $service->getLives();
		$this->response->servers = $servers;
	}

}

?>