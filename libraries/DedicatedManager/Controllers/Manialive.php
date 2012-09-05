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
	protected $defaultAction = 'config';

	function onConstruct()
	{
		parent::onConstruct();
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftText = _('Back to server');
		$header->leftLink = $this->request->createLinkArgList('../back-to-server');
	}
	
	function preFilter()
	{
		if(!$this->session->get('server'))
		{
			try
			{
				$service = new \DedicatedManager\Services\ServerService();
				$server = $service->getDetails($this->request->get('host'), $this->request->get('port'));
				$this->session->set('server', $server);
			}
			catch(\Exception $e)
			{
				$this->session->set('error', _('You cannot start ManiaLive: unknown server'));
				$this->request->redirectArgList('/');
			}
		}
	}
	
	function config($configFile = '')
	{
		$service = new \DedicatedManager\Services\ManialiveFileService();

		if($configFile)
		{
			$config = $service->get($configFile);
			$this->session->set('configFile', $configFile);
			$this->session->set('config', $config);
		}
		else
		{
			$config = new \DedicatedManager\Services\ManialiveConfig();
		}
		
		$this->response->configList = $service->getList();
		$this->response->config = $this->session->get('config', $config);
	}

	function setConfig($admins=array(), $logs=array(), $database=array(), $threading=array(), $wsapi=array())
	{
		$config = $this->session->get('config', new \DedicatedManager\Services\ManialiveConfig());
		$config->admins = array_filter($admins);
		$config->setLogsFromArray($logs);
		$config->setDatabaseFromArray($database);
		$config->setThreadingFromArray($threading);
		$config->setWsApiFromArray($wsapi);
		
		$this->session->set('config', $config);
		$this->request->redirectArgList('../plugins');
	}

	function plugins()
	{
		$config = $this->fetchAndAssertConfig(_('selecting plugins'));
		$service = new \DedicatedManager\Services\ManialiveService();
		$this->response->plugins = $service->getPlugins();
		$this->response->config = $config;
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../');
	}

	function setPlugins($plugins = array(), $other='')
	{
		$config = $this->session->get('config');
		$config->plugins = $plugins;
		$config->__other = $other;
		
		$this->request->redirectArgList('../preview');
	}

	function preview()
	{
		$config = $this->fetchAndAssertConfig(_('starting it'));
		$server = $this->session->get('server');
		$this->response->configFile = $this->session->get('configFile', \ManiaLib\Utils\Formatting::stripStyles($server->name));
		
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to plugins');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../plugins');
	}
	
	function start($configFile)
	{
		$config = $this->fetchAndAssertConfig(_('starting it'));
		$server = $this->session->get('server');
		$this->session->set('configFile', $configFile);
		
		$errors = array();
		if(strpbrk($configFile, '\\/:*?"<>|'))
		{
			$errors[] = _('The server config filename must not contain any of the following characters: \\ / : * ? " < > |');
		}
		
		if(!$errors)
		{
			try
			{
				$error = _('An error appeared while writing the server configuration file');
				$service = new \DedicatedManager\Services\ManialiveFileService();
				$service->save($configFile, $config);

				$error = _('An error appeared while starting ManiaLive');
				$service = new \DedicatedManager\Services\ManialiveService();
				$service->start($configFile, array(
						'address' => $server->rpcHost,
						'rpcport' => $server->rpcPort,
						'password' => $server->rpcPassword
					));
			}
			catch(\Exception $e)
			{
				\ManiaLib\Application\ErrorHandling::logException($e);
				$errors[] = $error;
			}
		}

		if($errors)
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../preview');
		}
		
		$this->session->set('success', _('ManiaLive has been successfully started'));
		$this->backToServer();
	}
	
	function backToServer()
	{
		$server = $this->session->get('server');
		$this->request->set('host', $server->rpcHost);
		$this->request->set('port', $server->rpcPort);
		
		$this->session->delete('configFile');
		$this->session->delete('config');
		$this->session->delete('server');
		$this->request->redirectArgList('/server/controllers', 'host', 'port');
	}
	
	protected function fetchAndAssertConfig($actionStr)
	{
		try
		{
			return $this->session->getStrict('config');
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to configure ManiaLive before %s.'), $actionStr));
			$this->request->redirectArgList('..');
		}
	}
}

?>