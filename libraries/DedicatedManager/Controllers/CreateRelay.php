<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision: 85 $:
 * @author      $Author: martin.gwendal $:
 * @date        $Date: 2012-08-03 17:56:01 +0200 (ven., 03 août 2012) $:
 */

namespace DedicatedManager\Controllers;

class CreateRelay extends Create
{
	function setConfig()
	{
		parent::setConfig();
		$this->request->redirectArgList('../spectate');
	}

	function spectate()
	{
		list($options) = $this->fetchAndAssertConfig(_('starting it'));

		$defaultFileName = \ManiaLib\Utils\Formatting::stripStyles($options->name);
		$this->response->configFile = $this->session->get('configFile', $defaultFileName);
		$service = new \DedicatedManager\Services\ServerService();
		$this->response->servers = $service->getLives();
		$this->response->spectate = $this->session->get('spectate', new \DedicatedManager\Services\Spectate());
		if(!$this->response->servers)
		{
			$this->response->spectate->method = 'login';
		}

		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->rightText = _('Back to relay server configuration');
		$header->rightIcon = 'back';
		$header->rightLink = $this->request->createLinkArgList('../config');
	}

	function start()
	{
		$configFile = $this->request->getPostStrict('configFile');
		$spectate = $this->request->getPostStrict('spectate');
		list($options, $account, $system, $authLevel, $isLan) = $this->fetchAndAssertConfig(_('starting it'));
		
		$spectate = \DedicatedManager\Services\Spectate::fromArray($spectate);
		switch($spectate->method)
		{
			case 'managed':
				try
				{
					list($rpcHost, $rpcPort, $rpcPass) = explode(':', $spectate->managed, 3);
					$connection = \DedicatedApi\Connection::factory($rpcHost, $rpcPort, 5, 'SuperAdmin', $rpcPass);
					$info = $connection->getSystemInfo();
					$gameServer = $info->publishedIp.':'.$info->port;
					$password = $connection->getServerPasswordForSpectator();
				}
				catch(\Exception $e)
				{
					$errors[] = _('Cannot retrieve server connection');
				}
				break;
			case 'ip':
				$gameServer = $spectate->ip.':'.$spectate->port;
				$password = $spectate->password;
				break;
			case 'login':
				$gameServer = $spectate->login;
				$password = $spectate->password;
				break;
		}
		
		$this->session->set('configFile', $configFile);
		$this->session->set('spectate', $spectate);
		
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
				$service = new \DedicatedManager\Services\ConfigFileService();
				$service->save($configFile, $options, $account, $system, $authLevel);

				$error = _('An error appeared while starting the server');
				$service = new \DedicatedManager\Services\ServerService();
				$server = new \DedicatedManager\Services\Server();
				$server->rpcHost = '127.0.0.1';
				$server->rpcPort = $service->startRelay($configFile, $gameServer, $password, $isLan);
				$server->rpcPassword = $authLevel->superAdmin;
				$service->register($server);
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
			$this->request->redirectArgList('../spectate');
		}

		$this->session->set('success', _('Your relay server has been successfully started'));
		$this->goHome();
	}
	
	function goHome()
	{
		$this->session->delete('spectate');
		parent::goHome();
	}
}

?>