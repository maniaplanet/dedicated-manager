<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class SystemConfig extends \Maniaplanet\DedicatedServer\Structures\AbstractStructure
{
	/** @var int */
	public $connectionUploadrate = 2000;
	/** @var int */
	public $connectionDownloadrate = 32000;
	/** @var bool */
	public $allowSpectatorRelays = false;
	/** @var int */
	public $p2pCacheSize = 600;
	/** @var string */
	public $forceIpAddress;
	/** @var int */
	public $serverPort = 2350;
	/** @var int */
	public $serverP2pPort = 3450;
	/** @var int */
	public $clientPort = 0;
	/** @var string */
	public $bindIpAddress;
	/** @var bool */
	public $useNatUpnp = true;
	/** @var int */
	public $xmlrpcPort = 5000;
	/** @var string */
	public $xmlrpcAllowremote = '127.0.0.1';
	/** @var string */
	public $blacklistUrl = '';
	/** @var string */
	public $guestlistFilename = '';
	/** @var string */
	public $blacklistFilename = '';
	/** @var string */
	public $title = '';
	/** @var string */
	public $minimumClientBuild = '';
	/** @var bool */
	public $disableCoherenceChecks = false;
	/** @var bool */
	public $useProxy = false;
	/** @var string */
	public $proxyLogin = '';
	/** @var string */
	public $proxyPassword = '';
}

?>