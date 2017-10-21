<?php

namespace TeamLeader\CustomerApi\Repository;

use GuzzleHttp\ClientInterface;
use Webmozart\Assert\Assert;
use Zend\Json\Json;

/**
 * TODO: implement a common repository interface
 * TODO: move to a distributable API client library
 */
final class Customers
{
    /** @var ClientInterface */
    private $api;

    /**
     * Customers constructor.
     * @param ClientInterface $api
     */
    public function __construct(ClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Find a customer by ID
     * @param int $id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function find(int $id): array
    {
        $response = $this->api->request('GET', '/api/customers/'.$id);
        if ($response->getStatusCode() >= 400) {
            // TODO: throw a more specific exception (include response, etc)
            throw new \RuntimeException('There was an error requesting the resource', $response->getStatusCode());
        }

        $body = $response->getBody()->getContents();
        Assert::notEmpty($body);

        return Json::decode($body);
    }
}
