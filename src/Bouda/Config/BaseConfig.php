<?php

namespace Bouda\Config;


class BaseConfig implements Config
{
	protected $config = [];


	public function get(string $section, string $variable = NULL)
	{
		$sectionElement = $this->getSection($section);

		if ($variable === NULL)
		{
			return $sectionElement;
		}

		if (isset($sectionElement[$variable]))
		{
			return $sectionElement[$variable];
		}

		throw new Exception('Config variable "' . $variable . '" in section "' . $section . '" not found.');
	}


	protected function getSection(string $section): array
	{
		$path = explode('.', $section);

		$currentElement = $this->config;

		// rekurzivne projit strom sekci
		foreach ($path as $section)
		{
			if (!isset($currentElement[$section]))
			{
				throw new Exception('Section "' . $section . '" not found.');
			}

			$currentElement = $currentElement[$section];
		}

		return $currentElement;
	}


	protected function checkIfFileExists(string $filename)
	{
		if (!file_exists($filename))
		{
			throw new Exception('Config file "' . $filename . '" not found.');
		}
	}
}
