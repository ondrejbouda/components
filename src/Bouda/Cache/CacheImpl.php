<?php

namespace Bouda\Cache;

use Bouda\Logger;


class CacheImpl
{
	public $cache = array();


	function save($object, string $key = NULL, string $version = NULL) : string
	{
		$serialized = serialize($object);


		if ($key === NULL)
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

		if ($version === NULL)
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


	public function load(string $key, $version = NULL)
	{
		if ($version === NULL)
		{
			if (!isset($this->cache[$key]))
			{
				return NULL;
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
				return NULL;
			}

			return unserialize($this->cache[$key][$version]);
		}
		
	}
}
