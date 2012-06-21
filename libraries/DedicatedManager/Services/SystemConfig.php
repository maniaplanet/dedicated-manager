<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class SystemConfig extends AbstractObject
{
	public $connectionUploadrate = 2000;
	public $connectionDownloadrate = 8920;
	public $allowSpectatorRelays = false;
	public $p2pCacheSize = 600;
	public $forceIpAddress;
	public $serverPort = 2350;
	public $serverP2pPort = 3450;
	public $clientPort = 0;
	public $bindIpAddress;
	public $useNatUpnp = true;
	public $xmlrpcPort = 5000;
	public $xmlrpcAllowremote = '127.0.0.1';
	public $blacklistUrl = '';
	public $guestlistFilename = '';
	public $blacklistFilename = '';
	public $title = '';
	public $minimumClientBuild = '';
	public $disableCoherenceChecks = false;
	public $useProxy = false;
	public $proxyLogin = '';
	public $proxyPassword = '';
}

?>