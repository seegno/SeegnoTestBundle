<?php

namespace Seegno\TestBundle\TestCase;

use JMS\Serializer\SerializationContext;
use Seegno\TestBundle\TestCase\IntegrationTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * TestCase for web tests.
 */
class WebTestCase extends IntegrationTestCase
{
    /**
     * Assert that object properties keys are in the response.
     */
    public function assertResponseFields(array $response, $object, array $groups = array())
    {
        $serializationContext = SerializationContext::create();
        $serializationContext->setSerializeNull(true);

        if (!empty($groups)) {
            $serializationContext->setGroups($groups);
        }

        $serializedObject = $this->get('serializer')->serialize($object, 'json', $serializationContext);

        $expectedKeys = array_keys(json_decode($serializedObject, true));
        $responseKeys = array_keys($response);

        $this->assertEquals($responseKeys, $expectedKeys);
    }

    /**
     * Authenticate user on a given client using the given firewall or the `securityContext` default firewall.
     */
    public function authenticateUser(Client $client, $user, $credentials, array $roles = array(), $firewall = null)
    {
        $securityContext = $this->get('security.context');
        $firewall = $firewall ?: $securityContext->getToken()->getProviderKey();

        $client->getCookieJar()->set(new Cookie(session_name(), true));

        // Bypass the `hasPreviousSession` check.
        $client->request('GET', '/');

        $token = new UsernamePasswordToken($user, $credentials, $firewall, $roles);
        $securityContext->setToken($token);

        $session = $client->getContainer()->get('session');
        $session->set(sprintf('_security_%s', $firewall), serialize($token));
        $session->save();
    }

    /**
     * Get client that simulates a browser and makes requests to a Kernel object.
     */
    public function getClient(array $parameters = array())
    {
        $client = static::createClient();
        $client->setServerParameters($parameters);

        return $client;
    }
}
