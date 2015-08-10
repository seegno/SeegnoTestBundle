<?php

namespace Seegno\TestBundle\TestCase;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Seegno\TestBundle\Exception\DatabaseNotFoundException;
use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * TestCase for integration tests.
 */
class IntegrationTestCase extends BaseTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->boot();
        $this->initializeDatabase();

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        self::$kernel->shutdown();

        parent::tearDown();
    }

    /**
     * Get an instance of the dependency injection container.
     */
    public function getContainer()
    {
        return $this->getKernel()->getContainer();
    }

    /**
     * Get session.
     */
    public function getSession()
    {
        return $this->getContainer()->get('session');
    }

    /**
     * Get Service.
     */
    public function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * Initialize databases.
     */
    public function initializeDatabase()
    {
        $dbDriver = $this->getContainer()->getParameter('seegno_test.database.driver');

        if ('ORM' === $dbDriver) {
            $this->initializeORM();
        } elseif ('ODM' === $dbDriver) {
            $this->initializeODM();
        }
    }

    /**
     * Boot.
     */
    protected function boot()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * Get kernel.
     */
    protected function getKernel()
    {
        return self::$kernel;
    }

    /**
     * Initialize ODM database.
     */
    protected function initializeODM()
    {
        $documentManager = $this->get('doctrine.odm.mongodb.document_manager');

        // Current database name.
        $dabaseName = $documentManager->getConfiguration()->getDefaultDB();

        if (!$dabaseName) {
            throw new DatabaseNotFoundException('MongoDB');
        }

        $documentManager->getConnection()->dropDatabase($dabaseName);
        $documentManager->getConfiguration()->setDefaultDB($dabaseName);
    }

    /**
     * Initialize ORM database.
     */
    protected function initializeORM()
    {
        $entityManager = $this->get('doctrine')->getManager();

        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();
    }
}
