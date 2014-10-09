#!/usr/bin/env php
<?php
require_once './libraries/autoload.php';

use DedicatedManager\Setup\SetupCommand;
use Symfony\Component\Console\Application;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
$application = new Application();
$application->add(new SetupCommand());
$application->setDefaultCommand('setup');
$application->run();