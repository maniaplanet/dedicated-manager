<?php
/**
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

use ManiaLib\Utils\StyleParser;

/**
 * Ugly Ajax controller
 */
class Ajax extends \ManiaLib\Application\Controller
{

	function formatting($input = '')
	{
		$this->response->disableDefaultViews();
		$this->response->resetViews();

		if(!$input)
		{
			die();
		}

		try
		{
			echo StyleParser::toHtml($input);
		}
		catch(\Exception $e)
		{
		}
		//$this->response->resetViews();
		die();
		//TODO: FUCKING UGLY
	}

	function mapData($filename = '')
	{
		$this->response->disableDefaultViews();
		$this->response->resetViews();

		if(!$filename)
		{
			return;
		}

		$info = pathinfo($filename);
		$service = new \DedicatedManager\Services\FileService();
		$map = $service->getData($info['basename'],$info['dirname'].'/');
		$data['name'] = StyleParser::toHtml($map->name);
		$data['author'] = $map->author;
		$data['goldTime'] = ($map->goldTime <= 0 ? '-' : \ManiaLive\Utilities\Time::fromTM($map->goldTime));
		$data['thumbnail'] = \ManiaLib\Application\Config::getInstance()->getImagesURL().'thumbnails/'.$map->uid.'.jpg';
		echo json_encode($data);
	}

}

?>