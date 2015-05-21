<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class ServiceRepository
{
	private $servicesByName = [];
	private $servicesByType = [];


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
