<?php
/**
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace DedicatedManager\Helpers\Box;

class Success extends Box
{
	public function __construct($message = null)
	{
		parent::__construct($message);
		$this->setClass('success-bar');
	}
}

?>