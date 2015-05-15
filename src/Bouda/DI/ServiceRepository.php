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


	public function add(string $serviceName, $service)
	{
		$this->servicesByName[$serviceName] = $service;

		$type = $this->config->get('services', $serviceName)['type'] ?? $this->config->get('services', $serviceName)['class'] ?? gettype($service);

		$this->servicesByType[$type] = $service;
	}


	public function getByName(string $serviceName)
	{
		return $this->servicesByName[$serviceName] ?? NULL;
	}
}
