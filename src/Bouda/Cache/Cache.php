<?php

namespace Bouda\Cache;


interface Cache
{
	function load(string $id, string $version);

	function save($object, string $id, string $version);
}
