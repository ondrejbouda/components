<?php

namespace Bouda\Cache;

use Bouda\Logger;


class CacheImpl implements Cache
{
	const SERIALIZED_SUFFIX = 'serialized';

	private $cacheDir;


	function __construct(string $cacheDir)
	{
		$this->cacheDir = $cacheDir;
	}


	public function load(string $id, string $version)
	{
		if ($this->isCached($id, $version))
		{
			Logger::log();

			return $this->loadFromCache($id, $version);
		}

		return NULL;
	}


	private function isCached(string $id, string $version) : bool
	{
		return file_exists($this->getCachedFilename($id, $version));
	}


	private function getCachedFilename(string $id, string $version) : string
	{
		return $this->cacheDir . $id . '.' . $version . '.' . self::SERIALIZED_SUFFIX;
	}


	private function loadFromCache(string $id, string $version)
	{
		return unserialize(file_get_contents($this->getCachedFilename($id, $version)));
	}



	public function save($object, string $id, string $version)
	{
		$this->invalidateCache($id);

		$filename = $this->getCachedFilename($id, $version);

		file_put_contents($filename, serialize($object));

		Logger::log($filename);
	}


	private function invalidateCache(string $id)
	{
		foreach (glob($this->cacheDir . $id . '.*') as $filename)
		{
			$this->deleteFile($filename);
		}
	}


	private function deleteFile($filename)
	{
		Logger::log($filename);

		unlink($filename);
	}
}
