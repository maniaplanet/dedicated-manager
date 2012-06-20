<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Helpers\Box;

abstract class Box
{

	protected $message;
	protected $type;
	protected $class;
	protected static $boxTypes = array(
		'error' => '\DedicatedManager\Helpers\Box\Error',
		'success' => '\DedicatedManager\Helpers\Box\Success',
		'warning' => '\DedicatedManager\Helpers\Box\Warning');

	public function __construct($message = null)
	{
		$this->setMessage($message);
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setClass($class)
	{
		$this->class = $class;
	}

	public function getClass()
	{
		return $this->class;
	}

	public function save()
	{
		return  '<div class="ui-bar ui-bar-e alert-bar '.$this->getClass().'">'.
					'<h3>'.$this->getMessage().'</h3>'.
					'<div>'.
						'<a href="#" data-role="button" data-icon="delete" data-iconpos="notext" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="e" class="'.$this->getClass().'">close</a>'.
					'</div>'.
				'</div>';
	}

	/**
	 * Display box, or boxes, automatically depending in session vars
	 * Usage:
	 * <?= NadeoLib\WebUI\Helpers\Box\Box::detect() ?>
	 * @param bool $delete Delete session var after usage
	 * @return string
	 */
	static public function detect($delete = true)
	{
		$session = \ManiaLib\Application\Session::getInstance();

		$result = '';

		foreach(self::$boxTypes as $type => $class)
		{
			$values = $session->get($type);
			if($values)
			{
				if(!is_array($values)) $values = array($values);

				foreach($values as $key => $value)
				{
					/* @var $error Box */
					$object = new $class;
					$object->setMessage($value);

					$result .= $object->save();
				}
			}
			if($delete)
			{
				$session->delete($type);
			}
		}
		return $result;
	}

	public function __toString()
	{
		return $this->save();
	}

}

?>