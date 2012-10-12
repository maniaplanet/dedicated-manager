<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

abstract class Create extends AbstractController
{
	protected $defaultAction = 'config';

	protected function onConstruct()
	{
		parent::onConstruct();
		$header = \DedicatedManager\Helpers\Header::getInstance();
		$header->leftLink = $this->request->createLinkArgList('../go-home');
	}
	
	function preFilter()
	{
		parent::preFilter();
		if(!$this->isAdmin)
		{
			$this->session->set('error', _('You need to be an admin to create a server.'));
			$this->request->redirectArgList('/');
		}
	}
	
	function config($configFile = '')
	{
		$service = new \DedicatedManager\Services\ConfigFileService();

		if($configFile)
		{
			list($options, $account, $system, $authLevel) = $service->get($configFile);
			$this->session->set('configFile', $configFile);
			$this->session->set('options', $options);
			$this->session->set('account', $account);
			$this->session->set('system', $system);
			$this->session->set('authLevel', $authLevel);
		}
		else
		{
			$options = new \DedicatedManager\Services\ServerOptions();
			$account = new \DedicatedManager\Services\Account();
			$system = new \DedicatedManager\Services\SystemConfig();
			$authLevel = new \DedicatedManager\Services\AuthorizationLevels();
		}
		
		$this->response->configFile = $configFile;
		$this->response->configList = $service->getList();
		$this->response->authLevel = $this->session->get('authLevel', $authLevel);
		$this->response->options = $this->session->get('options', $options);
		$this->response->account = $this->session->get('account', $account);
		$this->response->system = $this->session->get('system', $system);
	}

	function setConfig(array $options, array $account, array $system, array $authLevel, $isOnline = 0)
	{
		$options = \DedicatedManager\Services\ServerOptions::fromArray($options);
		$options->callVoteRatio = $options->callVoteRatio < 0 ? -1 : $options->callVoteRatio / 100;
		$options->nextCallVoteTimeOut = $options->nextCallVoteTimeOut * 1000;
		$account = \DedicatedManager\Services\Account::fromArray($account);
		$system = \DedicatedManager\Services\SystemConfig::fromArray($system);
		$authLevel = \DedicatedManager\Services\AuthorizationLevels::fromArray($authLevel);
		
		$this->session->set('options', $options);
		$this->session->set('account', $account);
		$this->session->set('system', $system);
		$this->session->set('authLevel', $authLevel);
		$this->session->set('isLan', !$isOnline);

		$service = new \DedicatedManager\Services\ConfigFileService();
		if( ($errors = $service->validate($options, $account, $system, $authLevel, !$isOnline)) )
		{
			$this->session->set('error', $errors);
			$this->request->redirectArgList('../config');
		}
	}
	
	function goHome()
	{
		$this->session->delete('options');
		$this->session->delete('account');
		$this->session->delete('system');
		$this->session->delete('authLevel');
		$this->session->delete('isLan');
		$this->request->redirectArgList('/');
	}
	
	protected function fetchAndAssertConfig($actionStr)
	{
		try
		{
			$options = $this->session->getStrict('options');
			$account = $this->session->getStrict('account');
			$system = $this->session->getStrict('system');
			$authLevel = $this->session->getStrict('authLevel');
			$isLan = $this->session->get('isLan');
			return array($options, $account, $system, $authLevel, $isLan);
		}
		catch(\Exception $e)
		{
			$this->session->set('error', sprintf(_('You need to configure the server before %s.'), $actionStr));
			$this->request->redirectArgList('../config');
		}
	}
}

?>