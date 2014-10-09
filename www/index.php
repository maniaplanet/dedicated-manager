<?php
define('DEDICATED_MANAGER_VERSION', '2.1.1');

require_once __DIR__.'/../libraries/autoload.php';
I18n\Functions::LOAD;
\ManiaLib\Application\Config::getInstance()->URL = 'http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/manager/';
\ManiaLib\Application\Bootstrapper::$errorReporting = E_ALL ^ E_DEPRECATED;
\ManiaLib\Application\Bootstrapper::run();
