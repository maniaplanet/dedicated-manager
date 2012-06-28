<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class Home extends AbstractController
{
	protected function onConstruct()
	{
		parent::onConstruct();
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftLink = null;
	}
	
	function index()
	{
		$this->request->registerReferer();
		$config = \DedicatedManager\Config::getInstance();
		
		$errors = array();
		$writables[] = $config->dedicatedPath;
		$currentDir = getcwd();
		if(file_exists($config->dedicatedPath.'UserData/Maps/MatchSettings/'))
		{
			$writables[] = $config->dedicatedPath.'UserData/Maps/MatchSettings/';
			chdir($config->dedicatedPath.'UserData/Maps/MatchSettings/');
			$tmp = glob('*.[tT][xX][tT]');
			$tmp = array_map(function ($a) use ($config) { return $config->dedicatedPath.'UserData/Maps/MatchSettings/'.$a; }, $tmp);
			$writables = array_merge($writables, $tmp);
		}
		$writables[] = $config->dedicatedPath.'UserData/Config/';
		$writables[] = $config->dedicatedPath.'Logs/';
		if(file_exists($config->dedicatedPath.'UserData/Config/'))
		{
			chdir($config->dedicatedPath.'UserData/Config/');
			$tmp = glob('*.[tT][xX][tT]');
			$tmp = array_map(function ($f) use ($config) { return $config->dedicatedPath.'UserData/Config/'.$f; }, $tmp);
			$writables = array_merge($writables, $tmp);
		}
		$writables[] = $config->manialivePath.'logs/';
		$writables[] = $config->manialivePath.'data/';
		chdir($currentDir);

		$executables[] = stripos(PHP_OS, 'win') !== false ? $config->dedicatedPath.'ManiaPlanetServer.exe' : $config->dedicatedPath.'ManiaPlanetServer';

		$failed = array_filter($writables, function ($f) { return file_exists($f) && !is_writable($f); });
		if($failed)
		{
			$errors[] = _('The following folders cannot be written by Apache user.').' '._('Contact the admin to check this.').'<br/>'.
					_('Folder list: ').'<ul>'.implode('', array_map(function ($f) { return '<li>'.$f.'</li>'; }, $failed)).'</ul>';
		}

		$failed = array_filter($executables, function ($f) { return file_exists($f) && !is_executable($f); });
		if($failed)
		{
			$errors[] = _('The following files cannot be executed by Apache user.').' '._('Contact the admin to check this.').'<br/>'.
					_('File list: ').'<ul>'.implode('', array_map(function ($f) { return '<li>'.$f.'</li>'; }, $failed)).'</ul>';
		}

		if($errors)
		{
			$this->session->set('error', $errors);
		}

		$service = new \DedicatedManager\Services\ServerService();
		$this->response->servers = $this->isAdmin ? $service->getLives() : $service->getLivesForManager($this->session->login);
	}
}

?>