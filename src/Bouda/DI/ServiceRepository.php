<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class ServiceRepository
{
	private $config;

	private $servicesByName = array();
	private $servicesByType = array();


	function __construct(Config $config)
	{
		$this->config = $config;
	}


	public function add(ServiceDefinition $serviceDefinition, $service)
	{
		$this->servicesByName[$serviceDefinition->getName()] = $service;
		$this->servicesByType[$serviceDefinition->getType()] = $service;
	}


	public function getByName(string $serviceName)
	{
		return $this->servicesByName[$serviceName] ?? NULL;
	}
}
