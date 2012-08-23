<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class Manialive extends AbstractController
{

	function index($host, $port)
	{
		$service = new \DedicatedManager\Services\ServerService();
		$server = $service->getDetails($host, $port);
		$this->response->server = $server;
		$this->response->backLink = $this->request->createLinkArgList('/server/', 'host', 'port');
		$this->session->set('server', $server);
	}

	function setConfig($enableThread = '', array $logs = array(), array $mysql = array())
	{
		$this->session->getStrict('server');
		if((int) $enableThread !== 0 && (int) $enableThread !== 1)
		{
			$this->session->set('error', _('threading can only be equal to 0 or 1'));
		}
		if(!count($logs))
		{
			$this->session->set('error', _('You have to configure logs'));
		}
		if(!count($mysql))
		{
			$this->session->set('error', _('You have to configure databasse'));
		}
		if($this->session->get('error'))
		{
			$server = $this->session->get('server');
			$this->request->set('host', $server->rpcHost);
			$this->request->set('port', $server->rpcPort);
			$this->request->redirectArgList('../', 'host', 'port');
		}
		$this->session->set('enableThread', $enableThread);
		$this->session->set('logs', $logs);
		$this->session->set('mysql', $mysql);

		$this->request->redirectArgList('../plugins');
	}

	function plugins()
	{
		try
		{
			$server = $this->session->getStrict('server');
			$this->session->getStrict('enableThread');
			$this->session->getStrict('logs');
			$this->session->getStrict('mysql');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('You need to configure the server before selecting plugins.'));
			$this->request->redirectArgList('../config');
		}
		$this->request->set('host', $server->rpcHost);
		$this->request->set('port', $server->rpcPort);
		$this->response->backLink = $this->request->createLinkArgList('../', 'host', 'port');
		$service = new \DedicatedManager\Services\ManialiveService();
		$plugins = $service->getPlugins();
		\ManiaLib\Utils\Logger::info($plugins);
		$this->response->plugins = $plugins;
	}

	function setPlugins(array $plugins = array())
	{
		try
		{
			$this->session->getStrict('server');
			$this->session->getStrict('enableThread');
			$this->session->getStrict('logs');
			$this->session->getStrict('mysql');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('You need to configure the server before selecting plugins.'));
			$this->request->redirectArgList('../plugins');
		}
		$this->session->set('plugins', $plugins);
		$this->request->redirectArgList('../advanced');
	}

	function advanced()
	{
		try
		{
			$server = $this->session->getStrict('server');
			$this->session->getStrict('enableThread');
			$this->session->getStrict('logs');
			$this->session->getStrict('mysql');
			$this->session->getStrict('plugins');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('You need to configure the server before selecting plugins.'));
			$this->request->redirectArgList('../plugins');
		}
		$this->request->set('host', $server->rpcHost);
		$this->request->set('port', $server->rpcPort);
		$this->response->backLink = $this->request->createLinkArgList('../', 'host', 'port');
	}
	
	function setAdvanced($advanced = '')
	{
		try
		{
			$this->session->getStrict('server');
			$this->session->getStrict('enableThread');
			$this->session->getStrict('logs');
			$this->session->getStrict('mysql');
			$this->session->getStrict('plugins');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', _('You need to configure the server before selecting plugins.'));
			$this->request->redirectArgList('../plugins');
		}
		$this->session->set('advanced',$advanced);
		$this->request->redirectArgList('../files');
	}

}

?>