<?php

namespace Bouda;


class WindowsCommandLine
{
	const PHP_CHARSET = 'UTF-8';

	private static $commandLineCharset;

	private static $charsetsCpToIconv = [
		852 => 'CP852',
	];

	public function __construct()
	{
		if (in_array('exec', explode(', ', ini_get('disable_functions'))))
		{
			throw new Exception('exec disabled');
		}

		exec('chcp', $output);
		$charset = substr($output[0], strpos($output[0], ':') + 2);

		if (!isset(self::$charsetsCpToIconv[$charset]))
		{
			throw new Exception('Unknown charset ' . $charset);
		}

		self::$commandLineCharset = self::$charsetsCpToIconv[$charset];
	}


	public function execute($command, $escape = true)
	{
		$command = $escape ? escapeshellcmd($command) : $command;
		// encode to cmd charset
		$command = self::encode($command);
		// send error output to std
		$command .= ' 2>&1';

		exec($command, $output, $return);

		if ($return !== 0)
		{
			throw new Exception('Error executing command: ' . self::decode(implode(' ', $output)));
		}

		return $output;
	}


	public static function encode(string $string) : string
	{
		return iconv(self::PHP_CHARSET, self::$commandLineCharset, $string);
	}


	public static function decode(string $string) : string
	{
		return iconv(self::$commandLineCharset, self::PHP_CHARSET, $string);
	}
}
