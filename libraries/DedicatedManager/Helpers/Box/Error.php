<?php
/**
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Helpers\Box;

class Error extends Box
{

	public function __construct($message = null)
	{
		parent::__construct($message);
		$this->setClass('error-bar');
	}
}
?>