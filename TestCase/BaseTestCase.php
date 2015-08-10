<?php

namespace Seegno\TestBundle\TestCase;

use \ReflectionProperty as ReflectionProperty;
use Faker\Factory as FakerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Base TestCase for unit and integration tests.
 */
class BaseTestCase extends WebTestCase
{
    protected $faker;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->faker = FakerFactory::create();

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->faker = null;

        parent::tearDown();
    }

    /**
     * Get faker instance.
     */
    public function getFaker()
    {
        return $this->faker;
    }

    /**
     * Get class mock.
     */
    protected function getClassMock($class, array $methods = null)
    {
        return $this
            ->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock()
        ;
    }

    /**
     * Set a class property using reflection.
     */
    protected function setReflectionProperty($class, $propertyName, $propertyValue)
    {
        $reflector = new ReflectionProperty(get_class($class), $propertyName);
        $reflector->setAccessible(true);
        $reflector->setValue($class, $propertyValue);
    }
}
