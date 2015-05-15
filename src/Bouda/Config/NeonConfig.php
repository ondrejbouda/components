<?php

namespace Bouda\Config;

use Nette\Neon\Decoder;


class NeonConfig extends BaseConfig
{
	function __construct(Decoder $decoder, string $filename)
	{
		$this->config = $decoder->decode(file_get_contents($filename));
	}
}