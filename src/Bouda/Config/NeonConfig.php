<?php

namespace Bouda\Config;


class NeonConfig extends BaseConfig
{
	public function __construct(NeonDecoder $decoder, string $filename)
	{
		$this->checkIfFileExists($filename);

		$this->config = $decoder->decode(file_get_contents($filename));
	}
}