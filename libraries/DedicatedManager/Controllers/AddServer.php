<?php
/**
 * @copyright   Copyright (c) 2009-2012 NADEO (http://www.nadeo.com)
 * @license     http://www.gnu.org/licenses/lgpl.html LGPL License 3
 * @version     $Revision$:
 * @author      $Author$:
 * @date        $Date$:
 */

namespace DedicatedManager\Controllers;

class AddServer extends AbstractController
{

    function index()
    {

    }

    function add($rpcHost, $rpcPort, $rpcPassword)
    {
        $serverCount = 0;
        $ports       = explode(',', $rpcPort);

        $server              = new \DedicatedManager\Services\Server();
        $server->rpcHost     = $rpcHost;
        $server->rpcPassword = $rpcPassword;
        foreach ($ports as $port) {
            $serverPort = trim($port);
            $matches = array();
            if(preg_match('/(\d*)\s*-\s*(\d*)/iu', $serverPort, $matches)){
                if($matches[1] > $matches[2]) {
                    continue;
                }
                for($i = $matches[1]; $i <= $matches[2]; $i++) {
                    $server->rpcPort = $i;
                    $serverCount += $this->doRegister($server) ? 1: 0;
                }
            } else {
                $server->rpcPort = $serverPort;
                $serverCount += $this->doRegister($server) ? 1 : 0;
            }
        }
        if ($serverCount == 0) {
            $this->session->set('error', 'Impossible to connect to server.');
        } elseif ($serverCount == 1) {
            $this->session->set(
                'success', sprintf('%d server has been added successfully', $serverCount)
            );
        } else {
            $this->session->set(
                'success', sprintf('%d servers have been added successfully', $serverCount)
            );
        }
        $this->request->redirectArgList('/');
    }

    protected function doRegister(\DedicatedManager\Services\Server $server)
    {
        $service = new \DedicatedManager\Services\ServerService();
        try {
            $service->register($server);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}