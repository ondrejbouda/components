<?php

namespace Bouda;


class Logger extends Bouda\Object
{
    private static $logFile;

    public static function setLogFile(string $logFile)
    {
        self::$logFile = $logFile;
    }

    // mlog:)
    public static function log($whatever = NULL)
    {
		if (isset(self::$logFile) && $fw = fopen(self::$logFile, 'a'))
        {
            $stack = debug_backtrace(0);

            ob_start();

            echo @$stack[1]['class'] . '::' . @$stack[1]['function'] . ' ';
            
            if ($whatever !== NULL)
            {
                print_r ($whatever);
            }

            echo chr(10);

            fwrite($fw, ob_get_clean());
            fclose($fw);
        }
    }
}