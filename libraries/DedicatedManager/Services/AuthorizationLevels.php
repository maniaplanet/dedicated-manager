<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class AuthorizationLevels extends \Maniaplanet\DedicatedServer\Structures\AbstractStructure
{
	/** @var string */
	public $superAdmin = 'SuperAdmin';
	/** @var string */
	public $admin = 'Admin';
	/** @var string */
	public $user = 'User';
}

?>