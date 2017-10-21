<?php

namespace App\Factory;

use App\ApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

/**
 * Builds a Guzzle client capable of querying our own api.
 * TODO: move to a PHP API library package
 */
class ApiClientFactory
{
    /**
     * @param ContainerInterface $container
     * @return ClientInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ClientInterface
    {
        $config = $container->get('config');
        Assert::keyExists($config, ApiClient::class);
        $apiConfig = $config[ApiClient::class];

        return new Client($apiConfig);
    }
}
