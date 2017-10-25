<?php

namespace TeamLeader\Domain\Sales\Discounts\Container;

use TeamLeader\Domain\Sales\Discounts\Action\BuyXGetYFree;
use TeamLeader\Domain\Sales\Discounts\Action\OrderDiscountPercent;
use TeamLeader\Domain\Sales\Discounts\Action\PercentDiscountOnCheapestItem;
use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Criteria\Item\FromCategory;
use TeamLeader\Domain\Sales\Discounts\Criteria\OrderItems;
use TeamLeader\Domain\Sales\Discounts\Factory\DiscountGranterFactory;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use TeamLeader\Domain\Sales\Discounts\Percentage;
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
     * TODO: refactor using \Zend\Di or similar to allow configuration-driven discounts more easily
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
            [ // Buy two or more products of category "Tools" (id 1) to get a 20% discount on the cheapest product.
                'name' => '20% off on one of two tools',
                'criteria' => new OrderItems(Expr::atLeast(2, new FromCategory(1))),
                'actions' => [
                    new PercentDiscountOnCheapestItem(new Percentage(20), new FromCategory(1)),
                ],
            ],
            [ // For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
                'name' => 'Buy 5 switches, get 1 free',
                'criteria' => new OrderItems(Expr::atLeast(1, $fiveOrMoreSwitches)),
                'actions' => [
                    new BuyXGetYFree(5, 1, $fiveOrMoreSwitches),
                ],
            ],
            [ // A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
                'name' => 'Loyalty Discount',
                'criteria' => new RevenueGreaterThan(1000),
                'actions' => [
                    new OrderDiscountPercent(new Percentage(10)),
                ],
            ],
        ];
    }
}
