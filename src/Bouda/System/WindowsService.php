<?php

namespace Bouda\System;


class WindowsService
{
    protected $cmd;

    protected $name;
    protected $path;

    public function __construct(CommandLine $cmd, $config)
    {
        $this->cmd = $cmd;
        $this->name = $config['name'];
        $this->path = $config['path'];
    }


    public function restart()
    {
        try
        {
            $this->cmd->execute('net stop ' . $this->name);
        }
        catch (\Exception $e) {}

        $this->cmd->execute('net start ' . $this->name);
    }


    public function getPath()
    {
        return $this->path;
    }
}