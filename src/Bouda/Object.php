<?php

namespace Bouda;


class Object
{
    public function __set(string $name, $value)
    {
        throw new Exception('Undeclared property "' . $name . '".');
    }
}
