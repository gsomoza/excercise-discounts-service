<?php

namespace TeamLeader\ProductApi;

use App\ApiClient;
use TeamLeader\ProductApi\Action;
use TeamLeader\ProductApi\Repository\Products;
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
                    Action\ListProductsAction::class,
                ],
                'factories' => [
                    Products::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                Products::class => [
                    ApiClient::class
                ],
            ],
        ];
    }
}
