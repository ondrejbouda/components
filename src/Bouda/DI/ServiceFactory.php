<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class ServiceFactory
{
	private $config;
	private $dependencyResolver;

	
	function __construct(Config $config, DependencyResolver $dependencyResolver)
	{
		$this->config = $config;
		$this->dependencyResolver = $dependencyResolver;
	}


	public function create(string $serviceName)
	{
		$serviceDefinition = $this->getServiceDefinition($serviceName);
		
		$dependencies = $this->getServiceDependencies($serviceDefinition);

		$class = $serviceDefinition->getClass();

		return new $class(...$dependencies);
	}


	private function getServiceDefinition(string $serviceName) : ServiceDefinition
	{
		try {
			return new ServiceDefinition($serviceName, $this->config->get('services', $serviceName));
		}
		catch (\Bouda\Config\Exception $e)
		{
			throw new Exception('Unknown service "' . $serviceName . '"');;
		}
	}


	private function getServiceDependencies(ServiceDefinition $serviceDefinition) : array
	{
		$dependencies = array();

		foreach ($serviceDefinition->getArguments() as $type => $value)
		{
			if (is_numeric($type))
			{
				$type = DependencyResolver::TYPE_STRING;
			}

			$dependencies[] = $this->dependencyResolver->resolveDependency($type, $value);
		}

		return $dependencies;
	}
}