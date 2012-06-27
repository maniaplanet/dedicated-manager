<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

class ManagerService extends AbstractService
{

	function getAllManagers()
	{
		return $this->db()->execute('SELECT DISTINCT login FROM Managers')->fetchArrayOfSingleValues();
	}

	function getList($hostname, $port)
	{
		return $this->db()->execute('SELECT login FROM Managers WHERE hostname = %s AND port = %d',
				$this->db()->quote($hostname), $port)->fetchArrayOfSingleValues();
	}

	function set($hostname, $port, $login)
	{
		$this->db()->execute('INSERT INTO Managers (hostname, port, login) VALUES (%s,%d,%s)', $this->db()->quote($hostname),
		$port, $this->db()->quote($login));
	}

	function revoke($hostname, $port, $login)
	{
		$this->db()->execute('DELETE FROM Managers WHERE hostname = %s AND port = %d AND login = %s', $this->db()->quote($hostname), $port, $this->db()->quote($login));
	}

}

?>