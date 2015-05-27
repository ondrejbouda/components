<?php

namespace Bouda\DITests;

use Tester\Assert,
    Tester\TestCase,
    Nette\Neon\Decoder,
    Bouda\DI\ServiceDefinition;

require_once __DIR__ . '/../../bootstrap.php';



class ServiceDefinitionTest extends TestCase
{
    /**
     * @throws Bouda\DI\Exception 'class' parameter of service definition not set
     */
    public function testCreateServiceDefinitionWithoutClassParameter()
    {
        new ServiceDefinition('foo', array());
    }


    public function testCreateServiceDefinition()
    {
        $args = array(
            'dependency' => 'someOtherService'
        );

        $serviceConfig = array(
            'class' => 'bar',
            'args' => $args,
        );

        $serviceDefinition = new ServiceDefinition('foo', $serviceConfig);

        Assert::equal('foo', $serviceDefinition->getName());
        Assert::equal('bar', $serviceDefinition->getType());
        Assert::equal('bar', $serviceDefinition->getClass());
        Assert::equal($args, $serviceDefinition->getArgs());
    }
}


$testCase = new ServiceDefinitionTest;
$testCase->run();
