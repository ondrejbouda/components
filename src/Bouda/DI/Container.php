<?php

namespace Bouda\DI;

use Bouda,
    Bouda\Config\Config;


class Container extends Bouda\Object
{
    private $config;

    private $serviceRepository;
    private $serviceFactory;

    private $alreadyCreating = [];


    public function __construct(Config $config, ServiceFactory $serviceFactory = null)
    {
        $this->config = $config;

        $this->serviceRepository = new ServiceRepository();
        $this->serviceFactory = $serviceFactory ?? new ConfigServiceFactory($config);
        $this->serviceFactory->injectContainer($this);

        $this->serviceRepository->add($this->serviceFactory->getServiceDefinition('config'), $config);
        $this->serviceRepository->add($this->serviceFactory->getServiceDefinition('container'), $this);
    }


    /**
     * Get service from repository by name. 
     * 
     * @param string $serviceName
     * @return mixed service
     * @throws Bouda\DI\Exception
     */
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


    /**
     * Get factory from repository by name. The registered service must be of type Bouda\DI\Factory.
     * 
     * @param string $factoryName
     * @return Bouda\DI\Factory
     * @throws Bouda\DI\Exception
     */
    public function getFactory(string $factoryName) : Factory
    {
        $factory = $this->getService($factoryName);

        if ($factory instanceof Factory)
        {
            return $factory;
        }

        throw new Exception('Service is not a factory.');
    }


    /**
     * Get instance from factory by name. The registered service must be of type Bouda\DI\Factory.
     * 
     * @param string $factoryName
     * @param mixed $args,... optional arguments passed to the factory creation method
     * @return mixed
     * @throws Bouda\DI\Exception
     */
    public function getInstanceFromFactory(string $factoryName, ...$args)
    {
        return $this->getFactory($factoryName)->create(...$args);
    }


    /**
     * Get resource from config by name.
     * 
     * @param string $resourceName
     * @return string resource value
     * @throws Bouda\Config\Exception
     */
    public function getResource(string $resourceName)
    {
        return $this->config->get('resources', $resourceName);
    }
}
