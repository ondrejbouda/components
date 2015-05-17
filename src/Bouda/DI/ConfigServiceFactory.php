<?php

namespace Bouda\DI;

use Bouda\Config\Config,
	Bouda\Logger;


class ConfigServiceFactory implements ServiceFactory
{
	private $config;
	private $dependencyResolver;

	
	function __construct(Config $config, Container $container)
	{
		$this->config = $config;
		$this->dependencyResolver = new DependencyResolver($container);
	}


	public function getServiceDefinition(string $serviceName) : ServiceDefinition
	{
		try {
			return new ServiceDefinition($serviceName, $this->config->get('services', $serviceName));
		}
		catch (\Bouda\Config\Exception $e)
		{
			throw new Exception('Unknown service "' . $serviceName . '"');;
		}
	}


	public function create(ServiceDefinition $serviceDefinition)
	{
		$dependencies = $this->getServiceDependencies($serviceDefinition);

		$class = $serviceDefinition->getClass();

		return new $class(...$dependencies);
	}


	private function getServiceDependencies(ServiceDefinition $serviceDefinition) : array
	{
		$dependencies = array();

		foreach ($serviceDefinition->getArgs() as $type => $value)
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