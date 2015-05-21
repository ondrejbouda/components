<?php

namespace Bouda\ConfigTests;

use Tester\Assert,
	Tester\TestCase,
	Bouda\Config\IniConfig,
	Bouda\Config\NeonConfig,
	Bouda\Config\NeonDecoderImpl;

require_once __DIR__ . '/../../bootstrap.php';



class CacheTest extends TestCase
{
	private $configIni;
	private $configNeon;


	public function setUp()
	{
		$this->configIni = new IniConfig('config.ini');
		$this->configNeon = new NeonConfig(new NeonDecoderImpl(), 'config.neon');
	}


	/**
	 * @throws Bouda\Config\Exception Config file "nonexistent.file" not found.
	 */
	public function testNonexistentFileIni()
	{
		new IniConfig('nonexistent.file');
	}


	/**
	 * @throws Bouda\Config\Exception Config file "nonexistent.file" not found.
	 */
	public function testNonexistentFileNeon()
	{
		new IniConfig('nonexistent.file');
	}


	public function testEquality()
	{
		Assert::equal($this->configIni->get('services'), $this->configNeon->get('services'));
	}


	public function testGetValue()
	{
		$expectedValue = 'logs/log.txt';
		Assert::equal($expectedValue, $this->configIni->get('resources', 'filename'));
		Assert::equal($expectedValue, $this->configNeon->get('resources', 'filename'));
	}

	public function testGetArray()
	{
		$expectedArray = [
			'filename' => 'logs/log.txt',
			'cacheDir' => 'cache/'
		];
		Assert::equal($expectedArray, $this->configIni->get('resources'));
		Assert::equal($expectedArray, $this->configNeon->get('resources'));
	}

	public function testGetByCompoundPath()
	{
		$expectedNestedArray = [
			'class' => 'Bouda\Container',
			'args' => NULL
		];
		Assert::equal($expectedNestedArray, $this->configIni->get('services.container'));
		Assert::equal($expectedNestedArray, $this->configIni->get('services', 'container'));
		Assert::equal($expectedNestedArray, $this->configNeon->get('services.container'));
		Assert::equal($expectedNestedArray, $this->configNeon->get('services', 'container'));
	}

	/**
	 * @throws Bouda\Config\Exception Section "nonexistent" not found.
	 */
	public function testNonexistentSectionIni()
	{
		$this->configNeon->get('nonexistent');
	}


	/**
	 * @throws Bouda\Config\Exception Config variable "nonexistent" in section "services" not found.
	 */
	public function testNonexistentVariableIni()
	{
		$this->configNeon->get('services', 'nonexistent');
	}
}


$testCase = new CacheTest;
$testCase->run();
