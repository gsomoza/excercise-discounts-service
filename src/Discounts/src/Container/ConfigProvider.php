<?php

namespace TeamLeader\Domain\Sales\Discounts\Container;

use TeamLeader\Domain\Sales\Discounts\Action\BuyXGetYFree;
use TeamLeader\Domain\Sales\Discounts\Action\OrderDiscountPercent;
use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Criteria\Item\FromCategory;
use TeamLeader\Domain\Sales\Discounts\Criteria\OrderItems;
use TeamLeader\Domain\Sales\Discounts\Factory\DiscountGranterFactory;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use TeamLeader\Domain\Sales\Discounts\Service\DiscountGranterService;
use Webmozart\Expression\Expr;
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
                    DiscountGranterService::class => DiscountGranterFactory::class,
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
     * TODO: refactor using \Zend\Di or similar to allow configuration-driven changes
     *
     * @return array
     */
    public function getEnabledDiscounts(): array
    {
        $fiveOrMoreSwitches = Expr::andX([
            new FromCategory(2),
            Expr::key('quantity', Expr::greaterThanEqual(5)),
        ]);

        return [
            [ // A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
                'name' => 'Loyalty Discount',
                'criteria' => new RevenueGreaterThan(1000.00),
                'actions' => [
                    new OrderDiscountPercent(10.00),
                ],
            ],
            [ // For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
                'name' => 'Buy 5 switches, get 1 free',
                'criteria' => new OrderItems(Expr::atLeast(1, $fiveOrMoreSwitches)),
                'actions' => [
                    new BuyXGetYFree(2, 1, $fiveOrMoreSwitches),
                ],
            ],
        ];
    }
}
