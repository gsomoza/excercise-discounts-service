<?php

namespace TeamLeader\ProductApi\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Webmozart\Assert\Assert;
use Zend\Json\Json;

/**
 * TODO: implement a common repository interface
 * TODO: move to a distributable API client library
 */
final class Products
{
    /** @var ClientInterface */
    private $api;

    /**
     * @param ClientInterface $api
     */
    public function __construct(ClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * Retrieves all products that have the given ids
     * @param array $ids
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findByIds(array $ids): array
    {
        Assert::allStringNotEmpty($ids);

        $response = $this->api->request('GET', '/api/products');
        Assert::eq($response->getStatusCode(), 200);

        $body = $response->getBody()->getContents();
        Assert::notEmpty($body);

        $products = \array_filter(
            Json::decode($body, Json::TYPE_ARRAY),
            function (array $product) use ($ids) {
                return \in_array($product['id'], $ids);
            }
        );

        $result = [];
        foreach ($products as $product) {
            $result[$product['id']] = $product;
        }

        return $result;
    }
}
