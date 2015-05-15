<?php

require_once('vendor/autoload.php');

use Nette\Neon\Decoder,
	Tracy\Debugger,
	Bouda\Config\ConfigNeon,
	Bouda\DI\Container,
	Bouda\Logger;


Debugger::enable();


$config = new ConfigNeon(new Decoder, 'config/config.neon');
Logger::setLogFile($config->get('resources', 'logFile'));
$container = new Container($config);
