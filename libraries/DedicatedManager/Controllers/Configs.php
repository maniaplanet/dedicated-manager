<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class Configs extends AbstractController
{
	function preFilter()
	{
		parent::preFilter();
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to manage files.'));
			$this->request->redirectArgList('/');
		}
	}

	function index()
	{
		$service = new \DedicatedManager\Services\ConfigFileService();
		$this->response->configFiles = $service->getList();
		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		$this->response->matchFiles = $service->getList();
		if(\DedicatedManager\Config::getInstance()->manialivePath)
		{
			$service = new \DedicatedManager\Services\ManialiveFileService();
			$this->response->manialiveFiles = $service->getList();
		}
	}

	function delete($configFiles=array(), $matchFiles=array(), $manialiveFiles=array())
	{
		$errors = array();
		$success = array();
		if($configFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->deleteList($configFiles);
				$success[] = _('Configuration files successfully deleted');
			}
			catch(\Exception $e)
			{
				$errors[] = _('Error while deleting configuration files');
			}
		}

		if($matchFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\MatchSettingsFileService();
				$service->deleteList($matchFiles);
				$success[] = _('Match settings files successfully deleted');
			}
			catch(\Exception $e)
			{
				$errors[] = _('Error while deleting match settings files');
			}
		}

		if($manialiveFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\ManialiveFileService();
				$service->deleteList($manialiveFiles);
				$success[] = _('ManiaLive configuration files successfully deleted');
			}
			catch(\Exception $e)
			{
				$errors[] = _('Error while deleting ManiaLive configuration files');
			}
		}

		if($errors)
		{
			$this->session->set('error', $errors);
		}
		if($success)
		{
			$this->session->set('success', $success);
		}

		$this->request->redirectArgList('..');
	}
}

?>