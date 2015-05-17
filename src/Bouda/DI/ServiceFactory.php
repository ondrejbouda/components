<?php

namespace Bouda\DI;


interface ServiceFactory
{
	function create(ServiceDefinition $serviceDefinition);
}
