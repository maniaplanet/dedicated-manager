<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class Manage extends \ManiaLib\Application\Controller
{

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
		$error = array();
		if($configFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->deleteList($configFiles);
			}
			catch(\Exception $e)
			{
				$error[] = 'configuration files';
			}
		}

		if($matchFiles)
		{
			try
			{
				$service = new \DedicatedManager\Services\MatchSettingsFileService();
				$service->deleteList($configFiles);
			}
			catch(\Exception $e)
			{
				$error[] = 'matchSettings files';
			}
		}

		if($error)
		{
			$this->session->set('error', sprintf(_('Error while deleting %s'), implode(',', $error)));
		}
		else
		{
			$this->session->set('success', _('Files successfully deleted'));
		}

		$this->request->redirectArgList('../');
	}

}

?>