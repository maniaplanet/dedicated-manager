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

namespace ManiaLib\Gui\Maniacode\Elements;

class InstallReplay extends FileDownload
{

	protected $xmlTagName = 'install_replay';

	function __construct($name='', $url='')
	{
		parent::__construct($name, $url);
	}

}

?>