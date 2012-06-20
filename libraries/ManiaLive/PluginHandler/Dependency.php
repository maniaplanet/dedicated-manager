<?php
/**
 * ManiaLive - TrackMania dedicated server manager in PHP
 * 
 * @copyright   Copyright (c) 2009-2011 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */
namespace ManiaLive\PluginHandler;

/**
 * Explains a Plugin's dependency.
 * For instnace if a Plugin uses functionality of another Plugin
 * you can tell the PluginHandler that it needs it to be loaded as well in order
 * to run properly. Is the dependency not loaded an Excpetion is thrown which causes
 * the handler to stop loading of the Plugin.
 * 
 * @author Florian Schnell
 */
final class Dependency
{
	const NO_LIMIT = null;
	
	private $min;
	private $max;
	private $pluginId;

	/**
	 * @param $plugin_name The name of the Plugin that is needed.
	 * @param $min The version which the Plugin at least has to have.
	 * @param $max Maximum version that is known to be supported.
	 */
	function __construct($pluginId, $min = self::NO_LIMIT, $max = self::NO_LIMIT)
	{
		$this->pluginId = $pluginId;
		$this->min = $min;
		$this->max = $max;
	}
	
	public function getMaxVersion()
	{
		return $this->max;
	}
	
	public function getMinVersion()
	{
		return $this->min;
	}
	
	public function getPluginId()
	{
		return $this->pluginId;
	}
}

class DependencyException extends \Exception {}

class DependencyNotFoundException extends DependencyException
{
	/**
	 * @param Plugin $plugin
	 * @param Dependency $dependency
	 */
	function __construct($plugin, $dependency)
	{
		parent::__construct('Plugin "'.$plugin->getId().'" needs "'.$dependency->getPluginId().'" to be installed!');
	}
}

class DependencyTooOldException extends DependencyException
{
	/**
	 * @param Plugin $plugin
	 * @param Dependency $dependency
	 */
	function __construct($plugin, $dependency)
	{
		parent::__construct('Plugin "'.$plugin->getId().'" needs "'.$dependency->getPluginId().'" to be at least version '.$dependency->getMinVersion().'!');
	}
}

class DependencyTooNewException extends DependencyException
{
	/**
	 * @param Plugin $plugin
	 * @param Dependency $dependency
	 */
	function __construct($plugin, $dependency)
	{
		parent::__construct('Plugin "'.$plugin->getId().'" needs an older version of "'.$dependency->getPluginId().'" to be installed, '.$dependency->getMaxVersion().' at highest!');
	}
}
?>