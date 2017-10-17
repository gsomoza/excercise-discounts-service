<?php

namespace TeamLeader\CustomerApi\Action;

use Fig\Http\Message\StatusCodeInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * GET /api/customer/:id
 */
class GetCustomerAction implements MiddlewareInterface
{
    /** @var \Traversable */
    private $customers;

    /**
     * GetCustomerAction constructor.
     */
    public function __construct()
    {
        // TODO: this is just to mock the CustomerApi microservice
        $this->customers = \json_decode(
            \file_get_contents(__DIR__ . '/../../test/fixtures/customers.json'),
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
        // input validation already exists at route level
        $id = (int) $request->getAttribute('id');

        foreach ($this->customers as $customer) {
            if ($id === (int) $customer['id']) {
                return new JsonResponse($customer);
            }
        }

        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
