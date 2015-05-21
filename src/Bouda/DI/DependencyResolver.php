<?php

namespace Bouda\DI;

use Bouda\Logger;


class DependencyResolver
{
	const TYPE_STRING = 'string';
	const TYPE_CLASS = 'class';
	const TYPE_RESOURCE = 'resource';
	const TYPE_SERVICE = 'service';


	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}



	public function resolveDependency(string $type, string $dependency)
	{
		switch ($type)
		{
			case self::TYPE_STRING:
			case self::TYPE_CLASS:
				return $this->resolveStringDependency($dependency);
				break;

			case self::TYPE_RESOURCE:
				return $this->resolveResourceDependency($dependency);
				break;

			case self::TYPE_SERVICE:
				return $this->resolveServiceDependency($dependency);
				break;

			default:
				throw new Exception('Cannot resolve dependency "' . $dependency . '" of type "' . $type . '"');
		}
	}


	private function resolveStringDependency(string $dependency)
	{
		return $dependency;
	}


	private function resolveResourceDependency(string $dependency) : string
	{
		$resource = $this->container->getResource($dependency);

		return $resource;
	}


	private function resolveServiceDependency(string $dependency)
	{
		$service = $this->container->getService($dependency);
			
		return $service;
	}
}
