<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Helpers;

class Header extends \ManiaLib\Utils\Singleton
{
	public $title;
	public $leftText;
	public $leftIcon;
	public $leftLink;
	public $rightText;
	public $rightIcon;
	public $rightLink;
	
	protected function __construct()
	{
		$r = \ManiaLib\Application\Request::getInstance();
		$this->title = 'Dedicated server Manager';
		$this->leftText = _('Back to home');
		$this->leftIcon = 'home';
		$this->leftLink = $r->createLinkArgList('/');
	}
	
	static function save()
	{
		$header = self::getInstance();
		return sprintf('<div data-position="fixed" data-role="header">%s<h1>%s</h1>%s</div>',
				$header->leftLink ? sprintf('<a href="%s" data-icon="%s" data-ajax="false">%s</a>',
						htmlentities($header->leftLink, ENT_QUOTES, 'UTF-8'), $header->leftIcon, $header->leftText) : '',
				$header->title,
				$header->rightLink ? sprintf('<a href="%s" data-icon="%s" data-ajax="false">%s</a>',
						htmlentities($header->rightLink, ENT_QUOTES, 'UTF-8'), $header->rightIcon, $header->rightText) : '');
	}
}

?>
