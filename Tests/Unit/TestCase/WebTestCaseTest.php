<?php

namespace Seegno\TestBundle\Tests\Unit\TestCase;

use Seegno\TestBundle\TestCase\BaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * WebTestCaseTest.
 *
 * @group unit
 */
class WebTestCaseTest extends BaseTestCase
{
    /**
     * SetUp.
     */
    public function setUp()
    {
        parent::setUp();

        $this->credentials = 'bar';
        $this->firewall = 'qux';
        $this->roles = array('waldo');
        $this->user = 'foo';

        $this->session = $this->getSessionMock(array('save', 'set'));

        $this->container = $this->getContainerMock(array('get'));
        $this->container->method('get')->with('session')->willReturn($this->session);

        $this->securityContext = $this->getSecurityContextMock(array());

        $this->client = $this->getClientMock(array());
        $this->client->method('getContainer')->willReturn($this->container);
        $this->client->method('getCookieJar')->willReturn($this->getCookieJarMock());
    }

    /**
     * Should call `cookieJar.set` with a new cookie.
     */
    public function testAuthenticateUserOnCookieJarSetting()
    {
        $cookieJar = $this->getCookieJarMock(array('set'));
        $cookieJar->expects($this->once())->method('set')->with(new Cookie(session_name(), true));

        $client = $this->getClientMock(array());
        $client->method('getContainer')->willReturn($this->container);
        $client->expects($this->once())->method('getCookieJar')->willReturn($cookieJar);

        $testCase = $this->getWebTestCaseMock(array('get'));
        $testCase->method('get')->with('security.context')->willReturn($this->securityContext);

        $testCase->authenticateUser($client, $this->user, $this->credentials, $this->roles, $this->firewall);
    }

    /**
     * Should call `client.request`.
     */
    public function testAuthenticateUserOnClientRequest()
    {
        $this->client->expects($this->once())->method('request') ->with('GET', '/');

        $testCase = $this->getWebTestCaseMock(array('get'));
        $testCase->method('get')->with('security.context')->willReturn($this->securityContext);

        $testCase->authenticateUser($this->client, $this->user, $this->credentials, $this->roles, $this->firewall);
    }

    /**
     * Should call `securityToken.setToken` with a new `UsernamePasswordToken`.
     */
    public function testAuthenticateUserOnSettingSecurityToken()
    {
        $token = new UsernamePasswordToken($this->user, $this->credentials, $this->firewall, $this->roles);

        $this->securityContext->expects($this->once())->method('setToken')->with($token);

        $testCase = $this->getWebTestCaseMock(array('get'));
        $testCase->method('get')->with('security.context')->willReturn($this->securityContext);

        $testCase->authenticateUser($this->client, $this->user, $this->credentials, $this->roles, $this->firewall);
    }

    /**
     * Should call `session.set` with the `security.context` token provider key if none is provided.
     */
    public function testAuthenticateUserIfNoFirewallIsProvided()
    {
        $token = new UsernamePasswordToken($this->user, $this->credentials, $this->firewall, $this->roles);

        $securityContextToken = $this
            ->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
            ->disableOriginalConstructor()
            ->setMethods(array('getProviderKey'))
            ->getMock()
        ;

        $securityContextToken->expects($this->once())->method('getProviderKey')->willReturn($this->firewall);

        $this->securityContext->expects($this->once())->method('getToken')->willReturn($securityContextToken);

        $this->session->expects($this->once())->method('set')->with('_security_qux', serialize($token));

        $testCase = $this->getWebTestCaseMock(array('get'));
        $testCase->method('get')->with('security.context')->willReturn($this->securityContext);

        $testCase->authenticateUser($this->client, $this->user, $this->credentials, $this->roles);
    }

    /**
     * Should call `session.set` with the given firewall.
     */
    public function testAuthenticateUserIfFirewallIsProvided()
    {
        $token = new UsernamePasswordToken($this->user, $this->credentials, $this->firewall, $this->roles);

        $this->session->expects($this->once())->method('set')->with('_security_qux', serialize($token));

        $testCase = $this->getWebTestCaseMock(array('get'));
        $testCase->method('get')->with('security.context')->willReturn($this->securityContext);

        $testCase->authenticateUser($this->client, $this->user, $this->credentials, $this->roles, $this->firewall);
    }

    /**
     * Get `AppKernel` mock.
     */
    protected function getAppKernelMock(array $methods = null)
    {
        return $this->getClassMock('Bundle\FrameworkBundle\Tests\Functional\app\AppKernel', $methods);
    }

    /**
     * Get `Client` mock.
     */
    protected function getClientMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Bundle\FrameworkBundle\Client', $methods);
    }

    /**
     * Get `Container mock.
     */
    protected function getContainerMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Component\DependencyInjection\Container', $methods);
    }

    /**
     * Get `CookieJar` mock.
     */
    protected function getCookieJarMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Component\BrowserKit\CookieJar', $methods);
    }

    /**
     * Get `SecurityContext` mock.
     */
    protected function getSecurityContextMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Component\Security\Core\SecurityContext', $methods);
    }

    /**
     * Get `Session` mock.
     */
    protected function getSessionMock(array $methods = null)
    {
        return $this->getClassMock('Symfony\Component\HttpFoundation\Session\Session', $methods);
    }

    /**
     * Get `WebTestCase` mock.
     */
    protected function getWebTestCaseMock(array $methods = null)
    {
        return $this->getClassMock('Seegno\TestBundle\TestCase\WebTestCase', $methods);
    }
}
