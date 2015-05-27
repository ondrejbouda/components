<?php

namespace BoudaTests;

use Tester\Assert,
    Tester\TestCase,
    Bouda\Object;

require_once __DIR__ . '/../bootstrap.php';


define('VALUE', 1);

class ChildObject extends Object
{
    public $publicProperty = VALUE;
}


class ObjectTest extends TestCase
{
    private $object;


    public function setUp()
    {
        $this->object = new ChildObject;
    }


    public function testExistingProperty()
    {
        Assert::equal(VALUE, $this->object->publicProperty);
    }


    /**
     * @throws Bouda\Exception Undeclared property "undeclaredProperty".
     */
    public function testUndeclaredProperty()
    {
        $this->object->undeclaredProperty = VALUE;
    }
}


$testCase = new ObjectTest;
$testCase->run();
