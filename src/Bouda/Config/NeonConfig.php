<?php

namespace Bouda\Config;


class NeonConfig extends BaseConfig
{
	function __construct(NeonDecoder $decoder, string $filename)
	{
		$this->config = $decoder->decode(file_get_contents($filename));
	}
}