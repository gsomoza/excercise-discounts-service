<?php

namespace TeamLeader\Domain\Sales\Discounts\Container;

use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Factory\DiscountGranterServiceFactory;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use TeamLeader\Domain\Sales\Discounts\Service\DiscountGranterService;
use Webmozart\Expression\Selector\AtLeast;

/**
 * Configuration provider for a ZF application
 */
final class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    ExpressionDiscountBuilder::class,
                ],
                'factories' => [
                    DiscountGranterService::class => DiscountGranterServiceFactory::class
                ],
            ],
            'teamleader' => [
                'domain' => [
                    'sales' => [
                        'discounts' => [
                            'enabled' => $this->getEnabledDiscounts(),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Returns an array of enabled discount configurations - this could actually lazy-load from e.g. a DB
     *
     * @return array
     */
    public function getEnabledDiscounts(): array
    {
        return [
            [
                'criteria' => [
                    'class' => RevenueGreaterThan::class,
                    'params' => [1000.00],
                ],
            ],
            [
                'criteria' => [
                    'class' => AtLeast::class,
                    'params' => [

                    ],
                ],
            ]
        ];
    }
}
