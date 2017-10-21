<?php

namespace TeamLeader\DiscountsApi;

use TeamLeader\CustomerApi\Repository\Customers;
use TeamLeader\DiscountsApi\Action\GrantAction;
use TeamLeader\Domain\Sales\Discounts\Service\DiscountGranterService;
use TeamLeader\ProductApi\Repository\Products;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    GrantAction::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                GrantAction::class => [
                    Products::class,
                    DiscountGranterService::class,
                    Customers::class,
                ],
            ],
        ];
    }
}
