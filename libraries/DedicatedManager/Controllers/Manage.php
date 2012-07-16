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

	function maps($path = '')
	{
		$service = new \DedicatedManager\Services\MapService();
		$files = $service->getList($path);
		usort($files,
			function (\DedicatedManager\Services\File $a, \DedicatedManager\Services\File $b)
			{
				$order = $b->isDirectory - $a->isDirectory;
				if(!$order)
				{
					$order = strcmp($a->filename, $b->filename);
				}
				return $order;
			}
		);

		$this->response->path = $path;
		$this->response->parentPath = preg_replace('/([^\\/]*\\/)$/ixu', '', $path);
		$this->response->files = $files;
	}

	function deleteMaps(array $maps, $path = '')
	{
		$service = new \DedicatedManager\Services\MapService();
		$service->delete($maps);
		$this->request->redirectArgList('../maps/', 'path');
	}

	function uploadMap($path = '')
	{
		$this->response->path = $path;
	}

	function doUploadMap()
	{
		if(!array_key_exists('path', $_POST))
		{
			$this->session->set('error', 'The path must be set');
			$this->request->redirect('../maps');
		}
		$this->request->set('path', $_POST['path']);
		if($_FILES['map']['error'])
		{
			switch($_FILES['map']['error'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->session->set('error', _('File is too big.'));
					break;
				case UPLOAD_ERR_INI_SIZE:
					$this->session->set('error', _('File is too big.'));
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->session->set('error', _('File is partially uploaded.'));
					break;
				case UPLOAD_ERR_NO_FILE:
					$this->session->set('error', _('No file uploaded.'));
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->session->set('error', _('Can\'t write the file on the disk.'));
					break;
			}
			$this->request->redirect('../maps', 'path');
		}

		if(!preg_match('/\\.map\\.gbx$/ixu', $_FILES['map']['name']))
		{
			$this->session->set('error', 'The file must be a ManiaPlanet map file');
			$this->request->set('path', $_POST['path']);
			$this->request->redirect('../upload-map', 'path');
		}
		$path = $_POST['path'];
		$service = new \DedicatedManager\Services\MapService();
		$service->upload($_FILES['map']['tmp_name'], $_FILES['map']['name'], $path);
		$this->request->redirectArgList('../maps', 'path');
	}

}

?>