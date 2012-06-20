<?php
/**
 * ManiaLib - Lightweight PHP framework for Manialinks
 * 
 * @see         http://code.google.com/p/manialib/
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace ManiaLib\Application\Filters;

/**
 * User agent checker
 * Forces GameBox user agent, redirects to maniaplanet.com otherwise
 */
class UserAgentCheck implements \ManiaLib\Application\Filterable
{

	protected static $callback = array('\ManiaLib\Application\Filters\UserAgentCheck', 'defaultHTMLView');

	static function isManiaplanet()
	{
		$userAgent = \ManiaLib\Utils\Arrays::get($_SERVER, 'HTTP_USER_AGENT');
		$expectedAgent = 'ManiaPlanet';
		$length = strlen($expectedAgent);
		if(strlen($userAgent) < $length || substr($userAgent, 0, $length) != $expectedAgent)
		{
			return false;
		}
		return true;
	}


	/**
	 * Sets the callback when someone tries to access the Manialink from outside the game.
	 * The callback prints some HTML and returns void.  
	 */
	static function setCallback($callback)
	{
		self::$callback = $callback;
	}

	/**
	 * This is the default HTML view when someone tries to access the Manialink from outside the game.
	 * You can override this default behaviour by changing the callback with \ManiaLib\Application\Filters\UserAgentCheck::setCallback()
	 */
	static function defaultHTMLView()
	{
		$MANIALINK = \ManiaLib\Application\Config::getInstance()->manialink;
		echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>$MANIALINK</title>
		<style type="text/css">
		
			body {
				background: #111111;
				color: #ffffff;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 12px;
				line-height: 15px;
			}
			
			#frame {
				width: 640px;
				margin: 75px auto;
			}
			
			h1 {
				color: #66ccff;
				text-align: center;
				margin-bottom: 50px;
			}
			
			p {
				text-align: justify;
			}
			
			a, a:visited {
				color: #66ccff;
				text-decoration: underline;
			}
			
			a:hover, a:active {
				color: #ffffff;
			}
		</style>
	</head>
	<body>
		<div id="frame">
			<h1>$MANIALINK</h1>
			<p>
			The page your are trying to access is a Manialink for Maniaplanet. 
			You can only view it using the in-game browser.
			<p>
			
			<p>
			To access it, <a href="maniaplanet:///:$MANIALINK">click here</a> or 
			launch Maniaplanet and go to the <b>$MANIALINK</b> Manialink.
			</p>
			
			<p>
			Maniaplanet is a series of fast-paced racing video games in which you 
			drive at mind-blowing speeds on fun and spectacular tracks in solo 
			and multi player modes. Several in-game editors allow for track 
			building, car painting or video editing.
			</p>
			
			<p>
			For more information, please visit <a href="http://www.maniaplanet.com">www.maniaplanet.com</a>
			</p>
		</div>
	</body>
</html>				
HTML;
		exit;
	}

	function preFilter()
	{
		if(!\ManiaLib\Application\Config::getInstance()->debug)
		{
			if(!self::isManiaplanet())
			{
				call_user_func(self::$callback);
			}
		}
	}

	function postFilter()
	{
		
	}

}

?>