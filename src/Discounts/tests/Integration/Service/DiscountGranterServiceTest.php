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

        // clean up variable data (e.g. timestamps) that won't work with snapthos
        foreach ($updatedOrder['discounts_applied'] as $key => $val) {
            $this->assertNotEmpty($val['granted']);
            unset($updatedOrder['discounts_applied'][$key]['granted']);
        }

        $this->assertMatchesJsonSnapshot(\json_encode($updatedOrder, JSON_PRETTY_PRINT));
    }
}
