<?php

namespace Bouda\DI;

use Bouda;


class ServiceDefinition extends Bouda\Object
{
	private $name;
	private $type;
	private $class;
	private $args = [];


	public function __construct(string $serviceName, array $serviceConfig)
	{
		if (!isset($serviceConfig['class']))
		{
			throw new Exception("'class' parameter of service definition not set");
		}

		$this->name = $serviceName;
		$this->type = $serviceConfig['type'] ?? $serviceConfig['class'];
		$this->class = $serviceConfig['class'];
		$this->args = $serviceConfig['args'] ?? [];
	}


	public function getName() : string
	{
		return $this->name;
	}


	public function getType() : string
	{
		return $this->type;
	}


	public function getClass() : string
	{
		return $this->class;
	}


	public function getArgs() : array
	{
		return $this->args;
	}
}
