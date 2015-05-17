<?php

namespace Bouda\DITests;

use Tester\Assert,
	Tester\TestCase,
	Nette\Neon\Decoder,
	Bouda\Config\Config,
	Bouda\Config\NeonConfig,
	Bouda\DI\Container,
	Bouda\DI\ServiceFactory;

require_once __DIR__ . '/../../bootstrap.php';



interface MockService {}

class MockServiceImpl implements MockService
{
	function __construct(Config $config, string $resource) {}
}

class MockClass
{
	function __construct(string $foo) {}
}



class ContainerTest extends TestCase
{
	private $container;


	public function setUp()
	{
		$this->container = new Container(new NeonConfig('config.neon'));
	}


	/**
	 * @throws Bouda\DI\Exception Unknown service "nonexistent"
	 */
	public function testGetNonexistentService()
	{
		$this->container->getService('nonexistent');
	}


	/**
	 * @throws Bouda\DI\Exception Cannot resolve dependency "bar" of type "foo"
	 */
	public function testGetServiceUnresolvable()
	{
		$mockService = $this->container->getService('mock_service_unresolvable');
	}


	/**
	 * @throws Bouda\DI\Exception Circular dependency found, cannot continue
	 */
	public function testCircularDependency()
	{
		$mockService = $this->container->getService('mock_service_circular1');
	}


	public function testGetService()
	{
		$mockService = $this->container->getService('mock_service');

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
		Assert::equal('foo', $this->container->getResource('resource'));
	}
}


$testCase = new ContainerTest;
$testCase->run();
