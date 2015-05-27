<?php

namespace Bouda\Cache;

use Bouda,
    Bouda\Logger;


class CacheImpl extends Bouda\Object implements Cache 
{
    public $cache = [];


    public function save($object, string $key = null, string $version = null) : string
    {
        $serialized = serialize($object);


        if ($key === null)
        {
            if (is_object($object))
            {
                $key = get_class($object) . spl_object_hash($object);
            }
            else
            {
                $key = md5($serialized);
            }
        }

        if ($version === null)
        {
            $this->cache[$key] = $serialized;
        }
        else
        {
            $this->cache[$key][$version] = $serialized;
        }

        return $key;
    }


    public static function getKeyFromFile(string $filename)
    {
        return md5($filename);
    }


    public function getVersionFromFile(string $filename)
    {
        return md5(filesize($filename) . filemtime($filename));
    }


    public function invalidateByKey($key)
    {
        unset($this->cache[$key]);
    }


    public function load(string $key, string $version = null)
    {
        if ($version === null)
        {
            if (!isset($this->cache[$key]))
            {
                return null;
            }

            if (is_array($this->cache[$key]))
            {
                throw new Exception('You must specify version');
            }

            return unserialize($this->cache[$key]);
        }
        else
        {
            if (!isset($this->cache[$key][$version]))
            {
                return null;
            }

            return unserialize($this->cache[$key][$version]);
        }
        
    }
}
