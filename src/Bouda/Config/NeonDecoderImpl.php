<?php

namespace Bouda\Config;

use Bouda,
	Nette\Neon\Neon;


class NeonDecoderImpl extends Bouda\Object implements NeonDecoder
{
	public function decode(string $input)
	{
		return Neon::decode($input);
	}
}
