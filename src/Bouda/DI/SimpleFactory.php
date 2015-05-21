<?php

namespace Bouda\DI;


class SimpleFactory implements Factory
{
	private $class;

	private $dependencies;


	public function __construct(string $class, ...$dependencies)
	{
		$this->class = $class;
		$this->dependencies = $dependencies;
	}


	public function create(...$args)
	{
		$args = array_merge($this->dependencies, $args);
		
		return new $this->class(...$args);
	}
}
