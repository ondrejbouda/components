<?php

namespace Bouda\DITests;

use Tester\Assert,
	Tester\TestCase,
	Bouda\Config\Config,
	Bouda\DI\Container,
	Bouda\DI\ServiceFactory,
	Bouda\DI\ServiceDefinition;

require_once __DIR__ . '/../../bootstrap.php';



class MockConfig implements Config
{
	public function get(string $section, string $variable)
	{
		return 'value';
	}
}


class MockServiceFactory implements ServiceFactory
{
	private $services = [
		'config' => [
			'class' => 'Bouda\Config\Config',
		],
		'container' => [
			'class' => 'Bouda\Config\Container',
		],
		'foo' => [
			'class' => 'Bouda\DITests\MockServiceImpl',
		],
		'simple_factory' => [
			'class' => 'Bouda\DI\SimpleFactory',
			'args' => [
				'Bouda\DITests\MockClass'
			],
		],
	];

	public function injectContainer(Container $container) {}

	public function getServiceDefinition(string $serviceName)
	{
		return new ServiceDefinition($serviceName, $this->services[$serviceName]);
	}

	public function create(ServiceDefinition $serviceDefinition)
	{
		$class = $serviceDefinition->getClass();
		return new $class(...$serviceDefinition->getArgs());
	}
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
		return new ServiceDefinition($serviceName, ['class' => 'Bouda\DITests\MockServiceImpl']);
	}

	public function create(ServiceDefinition $serviceDefinition)
	{
		$this->container->getService('foo');
		return new MockServiceImpl;
	}
}


interface MockService {}

class MockServiceImpl implements MockService {}

class MockClass
{
	function __construct(string $foo) {}
}



class ContainerTest extends TestCase
{
	private $container;


	public function setUp()
	{
		$this->container = new Container(new MockConfig, new MockServiceFactory);
	}


	/**
	 * @throws Bouda\DI\Exception Circular dependency found, cannot continue
	 */
	public function testCircularDependency()
	{
		$container = new Container(new MockConfig, new MockServiceFactoryCircular);
		$container->getService('foo');
	}


	public function testGetService()
	{
		$mockService = $this->container->getService('foo');

		Assert::type('Bouda\DITests\MockService', $mockService);
	}


	public function testGetFactory()
	{
		$factory = $this->container->getFactory('simple_factory');

		Assert::type('Bouda\DI\Factory', $factory);
		Assert::type('Bouda\DI\SimpleFactory', $factory);

		$instance = $this->container->getInstanceFromFactory('simple_factory', 'foo');

		Assert::type('Bouda\DITests\MockClass', $instance);
	}


	public function testGetResource()
	{
		Assert::equal('value', $this->container->getResource('resource'));
	}
}


$testCase = new ContainerTest;
$testCase->run();
