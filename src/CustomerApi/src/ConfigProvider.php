<?php

namespace TeamLeader\CustomerApi;

use App\ApiClient;
use TeamLeader\CustomerApi\Action;
use TeamLeader\CustomerApi\Repository\Customers;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

/**
 * Config Provider for container
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    Action\GetCustomerAction::class,
                ],
                'factories' => [
                    Customers::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                Customers::class => [
                    ApiClient::class,
                ],
            ],
        ];
    }
}
