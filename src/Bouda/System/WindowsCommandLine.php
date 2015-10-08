<?php

namespace Bouda\System;


class WindowsCommandLine implements CommandLine
{
    const PHP_CHARSET = 'UTF-8';

    private $fake;

    private static $commandLineCharset;

    private static $charsetsCpToIconv = array(
        852 => 'CP852',
    );


    private $log = array();


    public function __construct($fake = false)
    {
        $this->fake = $fake;

        if ($this->fake)
        {
            $charset = 852;
        }
        else
        {
            if (in_array('exec', explode(', ', ini_get('disable_functions'))))
            {
                throw new \Exception('exec disabled');
            }

            $this->exec('chcp', $output);
            $charset = substr($output[0], strpos($output[0], ':') + 2);

            if (!isset(self::$charsetsCpToIconv[$charset]))
            {
                throw new \Exception('Unknown charset ' . $charset);
            }
        }

        self::$commandLineCharset = self::$charsetsCpToIconv[$charset];
    }


    public function cd($dir = false)
    {
        if ($dir)
        {
            chdir($dir);
        }

        return getcwd();
    }


    public function getPrompt()
    {
        return $this->cd() . '>';
    }


    public function execute($command, $escape = true)
    {
        if (empty($command))
        {
            throw new \Exception("Empty command");
        }

        $log = new WindowsCommandLineLogEntry;
        $log->prompt = $this->getPrompt();
        $log->command = $command;

        $command = $escape ? escapeshellcmd($command) : $command;
        // encode to cmd charset
        $command = self::encode($command);
        // send error output to std
        $command .= ' 2>&1';

        $this->exec($command, $output, $return);

        $output = array_filter($output);
        $output = array_map(array('self','decode'), $output);

        $log->output = $output;

        $this->log[] = $log;

        if ($return !== 0)
        {
            throw new \Exception('Error executing command: ' . self::decode(implode(' ', $output)));
        }

        return $output;
    }


    public static function encode($string)
    {
        return iconv(self::PHP_CHARSET, self::$commandLineCharset, $string);
    }


    public static function decode($string)
    {
        return iconv(self::$commandLineCharset, self::PHP_CHARSET, $string);
    }


    public function getLog()
    {
        return $this->log;
    }


    private function exec($command, &$output = null, &$return = null)
    {
        if ($this->fake)
        {
            $output = array('Fake command executed.');
            $return = 0;
        }
        else
        {
            exec($command, $output, $return);
        }
    }
}
