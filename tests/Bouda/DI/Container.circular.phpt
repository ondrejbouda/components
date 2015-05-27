<?php

namespace Bouda\DITests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Config\Config;
use Bouda\DI\Container;
use Bouda\DI\ServiceFactory;
use Bouda\DI\ServiceDefinition;

require_once __DIR__ . '/../../bootstrap.php';



class MockConfig implements Config
{
    public function get(string $section, string $variable) {}
}


class MockServiceFactoryCircular implements ServiceFactory
{
    private $container;

    public function injectContainer(Container $container)
    {
        $this->container = $container;
    }

    public function getServiceDefinition(string $serviceName)
    {
        return new ServiceDefinition($serviceName, ['class' => 'Bouda\DITests\MockService']);
    }

    public function create(ServiceDefinition $serviceDefinition)
    {
        // simulate circular dependency
        $this->container->getService('foo');

        return new MockService;
    }
}

class MockService {}


class ContainerCircularTest extends TestCase
{
    /**
     * @throws Bouda\DI\Exception Circular dependency found, cannot continue
     */
    public function testCircularDependency()
    {
        $container = new Container(new MockConfig, new MockServiceFactoryCircular);
        $container->getService('foo');
    }
}


$testCase = new ContainerCircularTest;
$testCase->run();
