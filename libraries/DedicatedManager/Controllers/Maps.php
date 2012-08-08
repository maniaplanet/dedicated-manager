<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Controllers;

use DedicatedManager\Utils\GbxReader\Map;

class Maps extends AbstractController
{
	function preFilter()
	{
		parent::preFilter();
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to manage maps.'));
			$this->request->redirectArgList('/');
		}
	}

	function index($path = '')
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
		$this->response->parentPath = preg_replace('/([^\/]*\/)$/iu', '', $path);
		$this->response->files = $files;
	}

	function delete(array $maps = array(), $path = '')
	{
		if(!$maps)
		{
			$this->session->set('error',_('You have to select at least one map'));
			$this->request->redirect('..','path');
		}
		$service = new \DedicatedManager\Services\MapService();
		$service->delete($maps);
		$this->request->redirectArgList('..', 'path');
	}

	function upload()
	{
		if(!isset($_POST['path']))
		{
			$this->session->set('error', _('The path must be set.'));
			$this->request->redirect('..');
		}
		$this->request->set('path', $_POST['path']);
		
		if($_FILES['map']['error'])
		{
			switch($_FILES['map']['error'])
			{
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
			$this->request->redirect('..', 'path');
		}

		if(!preg_match('/\.map\.gbx$/iu', $_FILES['map']['name']) || !Map::check($_FILES['map']['tmp_name']))
		{
			$this->session->set('error', _('The file must be a ManiaPlanet map.'));
			$this->request->redirect('..', 'path');
		}
		
		$service = new \DedicatedManager\Services\MapService();
		$service->upload($_FILES['map']['tmp_name'], $_FILES['map']['name'], $_POST['path']);
		$this->request->redirectArgList('..', 'path');
	}
}

?>
