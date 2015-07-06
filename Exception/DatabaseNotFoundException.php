<?php

namespace Seegno\TestBundle\Exception;

use \Exception as Exception;

/**
 * DatabaseNotFoundException.
 */
class DatabaseNotFoundException extends Exception
{
    /**
     * Constructor.
     */
    public function __construct($type)
    {
        parent::__construct(sprintf('%s database was not found.', $type));
    }
}
