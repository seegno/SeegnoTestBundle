<?php

namespace Seegno\TestBundle\Fixtures;

use Doctrine\Common\Inflector\Inflector;
use Faker\Factory as FakerFactory;

/**
 * AbstractFixtures.
 */
abstract class AbstractFixtures
{
    protected $faker;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Set object properties.
     */
    public function setObjectProperties($object, $properties)
    {
        foreach ($properties as $key => $value) {
            $method = sprintf('set%s', Inflector::classify($key));

            if (false === method_exists($object, $method)) {
                continue;
            }

            $object->$method($value);
        }

        return $object;
    }
}
