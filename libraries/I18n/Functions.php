<?php
/**
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace I18n
{

	abstract class Functions
	{
		// This is just so the file can be autoloaded...

		const LOAD = null;

	}

}

namespace
{

	if(!function_exists('_'))
	{

		function _($message)
		{
			return $message;
		}

	}
}
?>