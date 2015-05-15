<?php

namespace Bouda\DI;


interface ServiceFactory
{
	function create(string $serviceName);
}
