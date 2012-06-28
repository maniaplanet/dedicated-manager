<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */

namespace DedicatedManager\Controllers;

abstract class AbstractController extends \ManiaLib\Application\Controller implements \ManiaLib\Application\Filterable
{
	protected $isAdmin;
	
	protected function onConstruct()
	{
		$config = \DedicatedManager\Config::getInstance();
		if($config->lanMode)
			$this->isAdmin = true;
		else
			$this->addFilter(new \ManiaLib\WebServices\ManiaConnectFilter());
		$this->addFilter($this);
	}

	public function preFilter()
	{
		$config = \DedicatedManager\Config::getInstance();
		if(!$config->lanMode)
			$this->isAdmin = in_array($this->session->login, $config->admins);
	}

	public function postFilter()
	{
		$this->response->isAdmin = $this->isAdmin;
	}
}

?>