<?php

namespace Bouda\Config;


class IniConfig extends BaseConfig
{
    public function __construct(string $filename)
    {
        $this->checkIfFileExists($filename);

        $parsedConfig = parse_ini_file($filename, true);

        $splitConfig = [];

        foreach ($parsedConfig as $key => $value)
        {
            $sections = explode('.', $key);

            $currentElement = &$splitConfig;

            foreach ($sections as $section)
            {
                if (!isset($currentElement[$section]))
                {
                    $currentElement[$section] = [];
                }
                
                $currentElement = &$currentElement[$section];
            }

            $currentElement = empty($value) ? null : $value;

            unset($currentElement);
        }

        // replace empty values for null
        array_walk_recursive($splitConfig, function(&$value, $key){
            $value = empty($value) ? null : $value;
        });

        $this->config = $splitConfig;
    }


    public function get(string $section, string $variable = null)
    {
        $nestedSection = $section . '.' . $variable;

        if (isset($this->config[$nestedSection]))
        {
            return $this->config[$nestedSection];
        }

        return parent::get($section, $variable);
    }
}
