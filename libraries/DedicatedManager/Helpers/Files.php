<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Helpers;

abstract class Files
{
	static function tree(array $files, array $selected = array(), $name = 'selected', $hideSelected = false)
	{
		return sprintf('<div class="sortable-container"><input type="hidden" class="sortable-result" name="%s" value="%s"/>',
				$name, implode('|', $selected)).self::subTree($files, $selected, $hideSelected).'</div>';
	}
	
	static function subTree(array $files, array $selected = array(), $hideSelected = false, $root='Maps')
	{
		$imageURL = \ManiaLib\Application\Config::getInstance()->getImagesURL();
		
		$displayedFiles = '';
		$displayedDirs = '';
		foreach($files as $file)
		{
			if($file instanceof \DedicatedManager\Services\Directory)
			{
				/* @var $file \DedicatedManager\Services\Directory */
				$displayedDirs .= self::subTree($file->children, $selected, $hideSelected, $file->filename);
			}
			else if($file instanceof \DedicatedManager\Services\Map)
			{
				$checked = in_array($file->path.$file->filename, $selected);

				if($hideSelected && $checked)
				{
					continue;
				}
				$id = uniqid('maps-');
				$displayedFiles .= sprintf(
						'<input type="checkbox" id="%s" value="%s"%s/>'.
						'<label for="%1$s" class="mapPreview">'.
							'<img src="%s" class="map-thumbnail" alt="thumbnail"/>'.
							'<span>%s</span>'.
						'</label>',
						$id,
						htmlentities($file->path.$file->filename, ENT_QUOTES | ENT_HTML5, 'utf-8'),
						$checked ? ' checked="checked"' : '',
						$imageURL.'thumbnails/'.$file->uid.'.jpg',
						\ManiaLib\Utils\StyleParser::toHtml($file->name).' '._('by').' '.$file->author);
			}
		}
		
		return '<div data-role="collapsible" data-collapsed="false" data-theme="b"><h5>'.$root.'</h5>'.
					($displayedFiles ? '<fieldset data-role="controlgroup">'.$displayedFiles.'</fieldset>' : '').
					$displayedDirs.
				'</div>';
	}
}

?>