<?php

namespace Bouda\DI;


interface ServiceFactory
{
	function injectContainer(Container $container);

	function getServiceDefinition(string $serviceName);
	
	function create(ServiceDefinition $serviceDefinition);
}
