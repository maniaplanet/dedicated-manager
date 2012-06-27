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
	function getAll()
	{
		return $this->db()->execute('SELECT DISTINCT login FROM Managers')->fetchArrayOfSingleValues();
	}

	function getList($rpcHost, $rpcPort)
	{
		return $this->db()->execute(
				'SELECT login FROM Managers WHERE rpcHost=%s AND rpcPort=%d', $this->db()->quote($rpcHost), $rpcPort
			)->fetchArrayOfSingleValues();
	}

	function grant($rpcHost, $rpcPort, $login)
	{
		$this->db()->execute(
				'INSERT INTO Managers (rpcHost, rpcPort, login) VALUES (%s,%d,%s)',
				$this->db()->quote($rpcHost),
				$rpcPort,
				$this->db()->quote($login));
	}

	function revoke($rpcHost, $rpcPort, $login)
	{
		$this->db()->execute(
				'DELETE FROM Managers WHERE rpcHost=%s AND rpcPort=%d AND login=%s',
				$this->db()->quote($rpcHost),
				$rpcPort,
				$this->db()->quote($login));
	}
}

?>