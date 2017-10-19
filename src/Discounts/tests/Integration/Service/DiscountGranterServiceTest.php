<?php

namespace TeamLeaderTests\Domain\Sales\Discounts;

use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use TeamLeader\Domain\Sales\Discounts\Container\ConfigProvider;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use TeamLeader\Domain\Sales\Discounts\Service\DiscountGranterService;
use TeamLeaderTests\Domain\Sales\Discounts\Fixtures\Order\OrderFixtures;

class DiscountGranterServiceTest extends TestCase
{
    use MatchesSnapshots;
    use OrderFixtures;

    public function test_discounts_granted()
    {
        $discountFactory = new ExpressionDiscountBuilder();
        $discountsConfig = (new ConfigProvider())->getEnabledDiscounts();

        $discounts = \array_map(function (array $discount) use ($discountFactory) {
            return $discountFactory->build($discount);
        }, $discountsConfig);

        $service = new DiscountGranterService($discounts);

        $originalOrder = $this->getOrderFixture('expanded1');

        $updatedOrder = $service->grantOnOrder($originalOrder);
        unset($updatedOrder['applied_discounts']); // because this has variable data (e.g. timestamps)

        $this->assertMatchesJsonSnapshot(\json_encode($updatedOrder, JSON_PRETTY_PRINT));
    }
}
