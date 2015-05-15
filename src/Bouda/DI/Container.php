<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class Container
{
	private $config;

	private $serviceRepository;
	private $serviceFactory;

	private $alreadyCreating = array();


	function __construct(Config $config)
	{
		$this->config = $config;

		$this->serviceRepository = new ServiceRepository($config);
		$this->serviceFactory = new ServiceFactory($config, new DependencyResolver($this));

		$this->serviceRepository->add('config', $config);
		$this->serviceRepository->add('container', $this);
	}


	public function getService(string $serviceName)
	{
		return $this->serviceRepository->getByName($serviceName) ?? $this->createService($serviceName);
	}


	private function createService(string $serviceName)
	{
		if (in_array($serviceName, $this->alreadyCreating))
		{
			throw new Exception('Circular dependency found, cannot continue');
		}

		$this->alreadyCreating[] = $serviceName;

		$service = $this->serviceFactory->create($serviceName);
		$this->serviceRepository->add($serviceName, $service);

		unset($this->alreadyCreating[$serviceName]);

		return $service;
	}


	public function getFactory(string $factoryName) : Factory
	{
		return $this->getService($factoryName);
	}


	public function getInstanceFromFactory(string $factoryName, ...$args)
	{
		return $this->getFactory($factoryName)->create(...$args);
	}


	public function getResource(string $resourceName)
	{
		return $this->config->get('resources', $resourceName);
	}
}
