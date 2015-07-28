<?php

namespace Seegno\TestBundle\Tests\Unit\TestCase;

use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * IntegrationTestCaseTest.
 *
 * @group unit
 */
class IntegrationTestCaseTest extends BaseTestCase
{
    /**
     * Should call `boot` and `initializeDatabases`.
     */
    public function testSetUp()
    {
        $testCase = $this->getTestCaseMock(array('boot', 'initializeDatabase'));
        $testCase->expects($this->once())->method('boot');
        $testCase->expects($this->once())->method('initializeDatabase');

        $testCase->setUp();
    }

    /**
     * Should call `kernel.getContainer` and return the result.
     */
    public function testGetContainer()
    {
        $kernel = $this->getClassMock('Symfony\Bundle\FrameworkBundle\Tests\Functional\app\AppKernel', array('getContainer'));
        $kernel->expects($this->once())->method('getContainer')->willReturn('foobar');

        $testCase = $this->getTestCaseMock(array('getKernel'));
        $testCase->expects($this->once())->method('getKernel')->willReturn($kernel);

        $this->assertEquals('foobar', $testCase->getContainer());
    }

    /**
     * Should call `container.get` with `session` and return the result.
     */
    public function testGetSession()
    {
        $container = $this->getContainerMock(array('get'));
        $container->expects($this->once())->method('get')->with('session')->willReturn('foobar');

        $testCase = $this->getTestCaseMock(array('getContainer'));
        $testCase->method('getContainer')->willReturn($container);

        $this->assertEquals('foobar', $testCase->getSession());
    }

    /**
     * Should call `container.get` with the given `service` and return the result.
     */
    public function testGet()
    {
        $container = $this->getContainerMock(array('get'));
        $container->expects($this->once())->method('get')->with('qux')->willReturn('foobar');

        $testCase = $this->getTestCaseMock(array('getContainer'));
        $testCase->method('getContainer')->willReturn($container);

        $this->assertEquals('foobar', $testCase->get('qux'));
    }

    /**
     * Should call `initializeORM` if `dbDriver` is `ORM`.
     */
    public function testInitializeDatabaseIfDbDriverIsORM()
    {
        $container = $this->getContainerMock(array('getParameter'));
        $container->expects($this->once())->method('getParameter')->with('seegno_test.database.driver')->willReturn('ORM');

        $testCase = $this->getTestCaseMock(array('getContainer', 'initializeORM'));
        $testCase->method('getContainer')->willReturn($container);
        $testCase->expects($this->once())->method('initializeORM');

        $testCase->initializeDatabase();
    }

    /**
     * Should call `initializeODM` if `dbDriver` is `ODM`.
     */
    public function testInitializeDatabaseIfDbDriverIsODM()
    {
        $container = $this->getContainerMock(array('getParameter'));
        $container->expects($this->once())->method('getParameter')->with('seegno_test.database.driver')->willReturn('ODM');

        $testCase = $this->getTestCaseMock(array('getContainer', 'initializeODM'));
        $testCase->method('getContainer')->willReturn($container);
        $testCase->expects($this->once())->method('initializeODM');

        $testCase->initializeDatabase();
    }

    /**
     * Should throw a `DatabaseNotFoundException` if default database name is not defined.
     *
     * @expectedException Seegno\TestBundle\Exception\DatabaseNotFoundException
     * @expectedExceptionMessage MongoDB database was not found.
     */
    public function testInitializeODMIfDatabaseNameIsNotDefined()
    {
        $container = $this->getContainerMock(array('getParameter'));
        $container->method('getParameter')->with('seegno_test.database.driver')->willReturn('ODM');

        $mongoDBConfiguration = $this->getMongoDBConfigurationMock(array('getDefaultDB'));
        $mongoDBConfiguration->expects($this->once())->method('getDefaultDB')->willReturn(null);

        $documentManager = $this->getDocumentManagerMock(array('getConfiguration'));
        $documentManager->method('getConfiguration')->willReturn($mongoDBConfiguration);

        $testCase = $this->getTestCaseMock(array('get', 'getContainer'));
        $testCase->method('getContainer')->willReturn($container);
        $testCase->method('get')->with('doctrine.odm.mongodb.document_manager')->willReturn($documentManager);

        $testCase->initializeDatabase();
    }

    /**
     * Should call `documentManager.getConnection.dropDatabase` and `documentManager.configuration.setDefaultDB` if `databaseName` is defined.
     */
    public function testInitializeODMIfDatabaseNameIsDefined()
    {
        $container = $this->getContainerMock(array('getParameter'));
        $container->method('getParameter')->with('seegno_test.database.driver')->willReturn('ODM');

        $mongoDBConnection = $this->getClassMock('Doctrine\MongoDB\Connection', array('dropDatabase'));
        $mongoDBConnection->expects($this->once())->method('dropDatabase')->with('foobar');

        $mongoDBConfiguration = $this->getMongoDBConfigurationMock(array('getDefaultDB', 'setDefaultDB'));
        $mongoDBConfiguration->method('getDefaultDB')->willReturn('foobar');
        $mongoDBConfiguration->expects($this->once())->method('setDefaultDB')->with('foobar');

        $documentManager = $this->getDocumentManagerMock(array('getConfiguration', 'getConnection'));
        $documentManager->method('getConfiguration')->willReturn($mongoDBConfiguration);
        $documentManager->method('getConnection')->willReturn($mongoDBConnection);

        $testCase = $this->getTestCaseMock(array('get', 'getContainer'));
        $testCase->method('getContainer')->willReturn($container);
        $testCase->method('get')->with('doctrine.odm.mongodb.document_manager')->willReturn($documentManager);

        $testCase->initializeDatabase();
    }

    /**
     * Get `container` mock.
     */
    protected function getContainerMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Component\DependencyInjection\Container', $methods);
    }

    /**
     * Get `IntegrationTestCase` mock.
     */
    protected function getTestCaseMock(array $methods = null)
    {
        return $this->getClassMock('Seegno\TestBundle\TestCase\IntegrationTestCase', $methods);
    }

    /**
     * Get `DocumentManager` mock.
     */
    protected function getDocumentManagerMock(array $methods = null)
    {
        return $this->getClassMock('Doctrine\ODM\MongoDB\DocumentManager', $methods);
    }

    /**
     * Get MongoDB Configuration mock.
     */
    protected function getMongoDBConfigurationMock(array $methods = null)
    {
        return $this->getClassMock('Doctrine\ODM\MongoDB\Configuration', $methods);
    }
}
