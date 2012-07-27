<?php
/**
 * ManiaLib - Lightweight PHP framework for Manialinks
 *
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Services;

/**
 * Abstract service
 */
abstract class AbstractService
{
	/**
	 * @var \ManiaLib\Database\Connection
	 */
	private $db;

	/**
	 * Returns an DB instance. Only instanciate the DB if needed, so if you have
	 * caching layer it will avoid creating DB connections for nothing.
	 *
	 * @return \ManiaLib\Database\Connection
	 */
	protected function db()
	{
		if(!$this->db)
		{
			$this->db = \ManiaLib\Database\Connection::getInstance();
		}
		return $this->db;
	}
}


?>