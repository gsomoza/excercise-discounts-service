<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Action;

use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use TeamLeader\Domain\Sales\Discounts\Action\PercentDiscountOnCheapestItem;
use TeamLeader\Domain\Sales\Discounts\Criteria\Item\FromCategory;
use TeamLeader\Domain\Sales\Discounts\Percentage;
use TeamLeaderTests\Domain\Sales\Discounts\Fixtures\Order\OrderFixtures;

class PercentDiscountOnCheapestItemTest extends TestCase
{
    use MatchesSnapshots;
    use OrderFixtures;

    /**
     * Apply the discount on the cheapest product in the order
     * @return void
     */
    public function test_discount_applied_successfully_simple()
    {
        $order = $this->getOrderFixture('expanded1');
        $discount = new Percentage(15.23);
        $action = new PercentDiscountOnCheapestItem($discount);
        $result = $action->apply($order);

        $this->assertMatchesJsonSnapshot(\json_encode($result));
    }

    /**
     * Uses a filter to apply the discount only on a subset of products
     * @return void
     */
    public function test_discount_applied_successfully_with_filter()
    {
        $order = $this->getOrderFixture('expanded1');
        $discount = new Percentage(15.23);
        $action = new PercentDiscountOnCheapestItem($discount, new FromCategory(2));
        $result = $action->apply($order);

        $this->assertMatchesJsonSnapshot(\json_encode($result));
    }

}
