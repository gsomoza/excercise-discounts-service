<?php

namespace TeamLeader\DiscountsApi\Action;

use GuzzleHttp\Exception\GuzzleException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TeamLeader\CustomerApi\Repository\Customers;
use TeamLeader\Domain\Sales\Discounts\Service\GrantsDiscounts;
use TeamLeader\ProductApi\Repository\Products;
use Webmozart\Assert\Assert;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Json\Json;

class GrantAction implements MiddlewareInterface
{
    /** @var Products */
    private $products;

    /** @var GrantsDiscounts */
    private $discounts;

    /** @var Customers */
    private $customers;

    /**
     * @param Products $products
     * @param GrantsDiscounts $discounts
     * @param Customers $customers
     */
    public function __construct(Products $products, GrantsDiscounts $discounts, Customers $customers)
    {
        $this->products = $products;
        $this->discounts = $discounts;
        $this->customers = $customers;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $order = Json::decode($request->getBody()->getContents(), Json::TYPE_ARRAY);

        if (!\is_array($order)) {
            throw new \InvalidArgumentException('Unprocessable entity', 422);
        }

        Assert::keyExists($order, 'items');
        Assert::notEmpty($order['items'], 'Expected at least 1 item in the order');

        try {
            // load customer data if that wasn't sent to us
            if (empty($order['customer'])) {
                Assert::keyExists($order, 'customer-id', 'Expected either "customer" data or "customer-id"');
                Assert::integerish($order['customer-id']);

                $order['customer'] = $this->customers->find($order['customer-id']);
            }

            // load product data if it wasn't sent to us
            $order['items'] = $this->loadProductDataForOrderItems($order['items']);

            // grant discounts on the order
            $json = $this->discounts->grantOnOrder($order);

            return new JsonResponse($json);

        } catch (GuzzleException $e) {
            // TODO: log it somewhere
            throw new \RuntimeException('Could not communicate with the Products API', 500, $e);
        } catch (\InvalidArgumentException $e) {
            // TODO: log it somewhere
            throw new \RuntimeException('Unexpected response from the Products API', 500, $e);
        }
    }

    /**
     * TODO: this does not quite belong in a controller, move to the model or a service
     * @param array $items
     * @return array
     * @throws GuzzleException
     * @throws \InvalidArgumentException
     */
    private function loadProductDataForOrderItems(array $items): array
    {
        $idsToProcess = \array_map(
            function(array $item) {
                Assert::keyExists($item, 'product-id');
                Assert::integerish($item['product-id']);
                return $item['product-id'];
            },
            \array_filter($items, function(array $item) {
                return empty($item['product']);
            })
        );

        $products = $this->products->findByIds($idsToProcess);

        return \array_map(function (array $item) use ($products) {
            if (empty($item['product'])) {
                $item['product'] = $products[$item['product-id']];
            }
            return $item;
        }, $items);
    }
}
