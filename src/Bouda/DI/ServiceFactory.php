<?php

namespace Bouda\DI;


interface ServiceFactory
{
	function getServiceDefinition(string $serviceName);
	
	function create(ServiceDefinition $serviceDefinition);
}
