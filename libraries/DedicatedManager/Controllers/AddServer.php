<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class AddServer extends AbstractController
{

	function index()
	{
		
	}

	function add($rpcHost, $rpcPort, $rpcPassword)
	{
		try
		{
			$service = new \DedicatedManager\Services\ServerService();
			$server = new \DedicatedManager\Services\Server();
			$server->rpcHost = $rpcHost;
			$server->rpcPort = $rpcPort;
			$server->rpcPassword = $rpcPassword;
			$service->register($server);
			$this->session->set('success', 'The server has been added successfully');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', 'Impossible to connect to the server.');
		}
		$this->request->redirectArgList('/');
	}

}

?>