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
		$configFiles = $service->getList();
		$service = new \DedicatedManager\Services\MatchSettingsFileService();
		$matchFiles = $service->getList();

		$this->response->configFiles = $configFiles;
		$this->response->matchFiles = $matchFiles;
	}

	function delete(array $configFiles = array(), array $matchFiles = array())
	{
		$errors = array();
		$success = array();
		if($configFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->deleteList($configFiles);
				$success[] = _('Configuration files successfully deleted.');
			}
			catch(\Exception $e)
			{
				$errors[] = _('Error while deleting configuration files.');
			}
		}

		if($matchFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\MatchSettingsFileService();
				$service->deleteList($matchFiles);
				$success[] = _('Match settings files successfully deleted.');
			}
			catch(\Exception $e)
			{
				$errors[] = _('Error while deleting match settings files.');
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