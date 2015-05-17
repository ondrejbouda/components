<?php

namespace Bouda\Config;

use Nette\Neon\Neon;


class NeonDecoderImpl implements NeonDecoder
{
	function decode(string $input)
	{
		return Neon::decode($input);
	}
}
