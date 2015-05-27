<?php

namespace Bouda\DI;

use Bouda;
use Bouda\Config\Config;


class ConfigServiceFactory extends Bouda\Object implements ServiceFactory
{
    private $config;
    private $dependencyResolver;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    public function injectContainer(Container $container)
    {
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
