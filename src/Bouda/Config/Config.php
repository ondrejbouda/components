<?php

namespace Bouda\Config;


interface Config
{
    function get(string $section, string $variable);
}
