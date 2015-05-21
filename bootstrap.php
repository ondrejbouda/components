<?php

require_once('vendor/autoload.php');

use Tracy\Debugger,
	Bouda\Config\NeonConfig,
	Bouda\Config\NeonDecoderImpl,
	Bouda\DI\Container,
	Bouda\Logger;


Debugger::enable();


$config = new NeonConfig(new NeonDecoderImpl, __DIR__ . '/config/config.neon');
Logger::setLogFile(__DIR__ . '/' . $config->get('resources', 'logFile'));
$container = new Container($config);
