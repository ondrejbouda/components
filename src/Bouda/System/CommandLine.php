<?php

namespace Bouda\System;


interface CommandLine
{
    public function cd($dir = false);

    public function getPrompt();

    public function execute($command, $escape = true);

    public function getLog();
}
