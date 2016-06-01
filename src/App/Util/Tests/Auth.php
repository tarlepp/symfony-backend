<?php

namespace App\Util\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Auth
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAuthorizationHeadersForUser($username = null, $password = null)
    {
        $client = $this->container->get('test.client');

        // Create request to make login using given credentials
        $client->request('POST', '/auth/getToken', ['username' => $username, 'password' => $password]);

        // Verify that login was ok
        if ($client->getResponse()->getStatusCode() !== 200) {
            throw new \Exception('User login failed...');
        }

        // Get response object
        $response = json_decode($client->getResponse()->getContent(), true);

        // Return valid authorization headers for user
        return $this->getAuthorizationHeaders(trim($response['token']));
    }

    public function getAuthorizationHeaders($token)
    {
        return [
            'CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . (string)$token,
        ];
    }
}
