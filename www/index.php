<?php
define('DEDICATED_MANAGER_VERSION', '1.5.1');

require_once __DIR__.'/../libraries/autoload.php';
I18n\Functions::LOAD;
\ManiaLib\Application\Bootstrapper::run();
?>