<?php

namespace Bouda\DI;

use Bouda\Logger;


class ServiceDefinition
{
	private $name;
	private $type;
	private $class;
	private $arguments = array();


	function __construct(string $name, array $serviceConfig)
	{
		$this->name = $name;
		$this->type = isset($serviceConfig['type']) ? $serviceConfig['type'] : $serviceConfig['class'];
		$this->class = $serviceConfig['class'];
		$this->arguments = $serviceConfig['args'];
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


	public function getArguments() : array
	{
		return $this->arguments;
	}
}
