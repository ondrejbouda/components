<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class Container
{
	private $config;

	private $serviceRepository;
	private $serviceFactory;

	private $alreadyCreating = [];


	function __construct(Config $config, ServiceFactory $serviceFactory = NULL)
	{
		$this->config = $config;

		$this->serviceRepository = new ServiceRepository($config);
		$this->serviceFactory = $serviceFactory ?? new ConfigServiceFactory($config, $this);

		$this->serviceRepository->add($this->serviceFactory->getServiceDefinition('config'), $config);
		$this->serviceRepository->add($this->serviceFactory->getServiceDefinition('container'), $this);
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

		$serviceDefinition = $this->serviceFactory->getServiceDefinition($serviceName);
		$service = $this->serviceFactory->create($serviceDefinition);

		$this->serviceRepository->add($serviceDefinition, $service);

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
