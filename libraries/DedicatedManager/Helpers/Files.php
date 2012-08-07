<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Helpers;

use DedicatedManager\Services\Directory;
use DedicatedManager\Services\Map;
use ManiaLib\Application\Config;
use ManiaLib\Utils\StyleParser;

abstract class Files
{
	static function map(Map $map, $name='maps[]', $checked=false, $withThumbnail=true, $disabled=false, $readonly=false)
	{
		$id = uniqid('maps-');
		$str = sprintf(
				'<input type="checkbox" id="%s" name="%s" value="%s"%s%s%s/>',
				$id, $name, htmlentities($map->path.$map->filename, ENT_QUOTES | ENT_HTML5, 'utf-8'),
				$checked ? ' checked="checked"' : '', $disabled ? ' disabled="disabled"' : '', $readonly ? ' class="readonly-checkbox"' : ''
			);
		
		$label = sprintf(_('%s by %s'), StyleParser::toHtml($map->name), $map->authorNick ? StyleParser::toHtml($map->authorNick) : $map->authorLogin);
		
		if($withThumbnail)
			$str .= sprintf(
				'<label for="%s">'.
					'<img src="%s" class="map-thumbnail" alt="thumbnail"/>'.
					'<span>%s</span>'.
				'</label>',
				$id, Config::getInstance()->getImagesURL().'thumbnails/'.$map->uid.'.jpg', $label
			);
		else
			$str .= sprintf('<label for="%s">%s</label>',$id, $label);
		
		return $str;
	}
	
	static function rawMap(\DedicatedApi\Structures\Map $map, $name='maps[]', $checked=false, $disabled=false, $readonly=false)
	{
		$id = uniqid('maps-');
		$str = sprintf(
				'<input type="checkbox" id="%s" name="%s" value="%s"%s%s%s/>',
				$id, $name, htmlentities($map->fileName, ENT_QUOTES | ENT_HTML5, 'utf-8'),
				$checked ? ' checked="checked"' : '', $disabled ? ' disabled="disabled"' : '', $readonly ? ' class="readonly-checkbox"' : ''
			);
		
		$label = sprintf(_('%s by %s'), StyleParser::toHtml($map->name), $map->author);
		$str .= sprintf(
			'<label for="%s">'.
				'<img src="%s" class="map-thumbnail" alt="thumbnail"/>'.
				'<span>%s</span>'.
			'</label>',
			$id, Config::getInstance()->getImagesURL().'thumbnails/'.$map->uId.'.jpg', $label
		);
		
		return $str;
	}
	
	static function folder(array $files, $path='', $parentPath='', $name = 'maps[]', array $selected = array())
	{
		$r = \ManiaLib\Application\Request::getInstance();
		$displayedDirs = '';
		$displayedFiles = '';
		foreach($files as $file)
		{
			if($file instanceof Directory)
			{
				/* @var $file Directory */
				$r->set('path', $file->path.$file->filename.'/');
				$displayedDirs .= sprintf(
						'<li><a href="%s" data-ajax="false">%s</a></li>',
						$r->createLinkArgList('.', 'path'),
						$file->filename
					);
			}
			else if($file instanceof Map)
			{
				$checked = in_array($file->path.$file->filename, $selected);
				$displayedFiles .= self::map($file, $name, $checked, false);
			}
		}
		
		$r->set('path', $parentPath);
		$str = '<ul data-role="listview" data-inset="true">'.
					'<li data-role="list-divider"><h3>Maps/'.$path.'</h3></li>'.
					($path != $parentPath ? sprintf('<li data-icon="arrow-u" data-theme="e"><a href="%s" data-ajax="false">%s</a></li>', $r->createLinkArgList('.', 'path'), _('Parent directory')) : '').
					$displayedDirs.
					($displayedFiles ? '<li><fieldset data-role="controlgroup">'.$displayedFiles.'</fieldset></li>' : '').
				'</ul>';
		
		$r->restore('path');
		return $str;
	}
	
	static function sortableTree(array $files, array $selected = array(), $name = 'selected', $hideSelected = false)
	{
		return sprintf('<div class="sortable-container"><input type="hidden" class="sortable-result" name="%s" value="%s"/>',
				$name, implode('|', $selected)).self::subTree($files, $selected, $hideSelected).'</div>';
	}
	
	private static function subTree(array $files, array $selected = array(), $hideSelected = false, $root='Maps')
	{
		$displayedFiles = '';
		$displayedDirs = '';
		foreach($files as $file)
		{
			if($file instanceof Directory)
			{
				/* @var $file Directory */
				$displayedDirs .= self::subTree($file->children, $selected, $hideSelected, $file->filename);
			}
			else if($file instanceof Map)
			{
				$checked = in_array($file->path.$file->filename, $selected);

				if($hideSelected && $checked)
				{
					continue;
				}
				
				$displayedFiles .= self::map($file, '', $checked);
			}
		}
		
		return '<div data-role="collapsible" data-collapsed="false" data-theme="b"><h5>'.$root.'</h5>'.
					($displayedFiles ? '<fieldset data-role="controlgroup">'.$displayedFiles.'</fieldset>' : '').
					$displayedDirs.
				'</div>';
	}
}

?>