<?php

namespace Seegno\TestBundle\Tests\Unit\TestCase;

use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;
use \ReflectionProperty as ReflectionProperty;
use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * BaseTestCaseTest.
 *
 * @group unit
 */
class BaseTestCaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Should initialize `faker` property.
     */
    public function testSetUp()
    {
        $testCase = $this
            ->getMockBuilder('Seegno\TestBundle\TestCase\BaseTestCase')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock()
        ;

        $testCase->setUp();

        $this->assertInstanceOf('Faker\Generator', $testCase->getFaker());
    }

    /**
     * Should set `faker` as `null`.
     */
    public function testTearDown()
    {
        $testCase = $this
            ->getMockBuilder('Seegno\TestBundle\TestCase\BaseTestCase')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock()
        ;

        $reflector = new ReflectionProperty('Seegno\TestBundle\TestCase\BaseTestCase', 'faker');
        $reflector->setAccessible(true);
        $reflector->setValue($testCase, 'foobar');

        $testCase->teardown();

        $this->assertEquals(null, $testCase->getFaker());
    }

    /**
     * Should return `faker` property.
     */
    public function testGetFaker()
    {
        $testCase = new BaseTestCase();

        $reflector = new ReflectionProperty('Seegno\TestBundle\TestCase\BaseTestCase', 'faker');
        $reflector->setAccessible(true);
        $reflector->setValue($testCase, 'foobar');

        $this->assertEquals('foobar', $testCase->getFaker());
    }
}
