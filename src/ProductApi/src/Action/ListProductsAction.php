<?php

namespace TeamLeader\ProductApi\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * GET /api/product
 */
class ListProductsAction implements MiddlewareInterface
{
    /** @var \Traversable */
    private $products;

    /**
     * GetCustomerAction constructor.
     */
    public function __construct()
    {
        // TODO: this is just to mock the ProductApi microservice
        $this->products = \json_decode(
            \file_get_contents(__DIR__ . '/../../test/fixtures/products.json'),
            true
        );
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
        return new JsonResponse($this->products);
    }
}
