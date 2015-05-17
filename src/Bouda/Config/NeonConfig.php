<?php

namespace Bouda\Config;

use Nette\Neon\Neon;


class NeonConfig extends BaseConfig
{
	function __construct(string $filename)
	{
		$this->config = Neon::decode(file_get_contents($filename));
	}
}