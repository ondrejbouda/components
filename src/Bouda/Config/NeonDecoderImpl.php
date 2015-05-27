<?php

namespace Bouda\Config;

use Bouda;
use Nette\Neon\Neon;


class NeonDecoderImpl extends Bouda\Object implements NeonDecoder
{
    public function decode(string $input)
    {
        return Neon::decode($input);
    }
}
