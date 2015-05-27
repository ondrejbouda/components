<?php

namespace Bouda\CacheTests;

use Tester\Assert,
    Tester\TestCase,
    Tester\FileMock,
    Bouda\Cache\CacheImpl;

require_once __DIR__ . '/../../bootstrap.php';



class CacheTest extends TestCase
{
    private $cache;


    public function setUp()
    {
        $this->cache = new CacheImpl();
    }


    public function testLoadingByNonexistentKey()
    {
        $retrievedObject = $this->cache->load(123456789);

        Assert::null($retrievedObject);
    }


    public function testCachingStringWithDefaultKey()
    {
        $originalObject = 'whatava';

        $key = $this->cache->save($originalObject);

        $retrievedObject = $this->cache->load($key);

        Assert::equal($originalObject, $retrievedObject);
    }


    public function testCachingObjectWithDefaultKey()
    {
        $originalObject = new \StdClass;
        $originalObject->var = 'whatava';

        $key = $this->cache->save($originalObject);

        $retrievedObject = $this->cache->load($key);

        Assert::equal($originalObject, $retrievedObject);
    }


    public function testCachingObjectWithExternalKey()
    {
        $originalObject = new \StdClass;
        $originalObject->id = 123456;

        $this->cache->save($originalObject, $originalObject->id);

        $retrievedObject = $this->cache->load($originalObject->id);

        Assert::equal($originalObject, $retrievedObject);
    }


    public function testCachingObjectWithExternalKeyAndVersionFromFile()
    {
        $originalObject = new \StdClass;
        $originalObject->file = FileMock::create('whatava');

        $key = $this->cache->getKeyFromFile($originalObject->file);
        $version = $this->cache->getVersionFromFile($originalObject->file);

        $this->cache->save($originalObject, $key, $version);

        $retrievedObject = $this->cache->load($key, $version);

        Assert::equal($originalObject, $retrievedObject);

        // change file
        $originalObject->file = FileMock::create('whatava2');
        $newVersion = $this->cache->getVersionFromFile($originalObject->file);

        $retrievedObject = $this->cache->load($key, $newVersion);

        Assert::null($retrievedObject);
    }


    public function testInvalidateCacheByKey()
    {
        $originalObject = 'whatava';

        $key = $this->cache->save($originalObject);

        $this->cache->invalidateByKey($key);

        $retrievedObject = $this->cache->load($key);

        Assert::null($retrievedObject);
    }
}


$testCase = new CacheTest;
$testCase->run();
