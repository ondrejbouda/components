<?php

namespace Bouda\DI;

use Bouda,
	Bouda\Config\Config;


class ServiceRepository extends Bouda\Object
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
