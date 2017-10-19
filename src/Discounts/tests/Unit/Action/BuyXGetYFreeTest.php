<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Action;

use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use TeamLeader\Domain\Sales\Discounts\Action\BuyXGetYFree;
use TeamLeader\Domain\Sales\Discounts\Criteria\Item\FromCategory;
use TeamLeaderTests\Domain\Sales\Discounts\Fixtures\Order\OrderFixtures;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;

/**
 * Class BuyXGetYFreeTest
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class BuyXGetYFreeTest extends TestCase
{
    use MatchesSnapshots;
    use OrderFixtures;

    /**
     * @param int $threshold
     * @param int $qtyFree
     * @param Expression $filterCriteria
     * @return void
     * @dataProvider orderWithItemsProvider
     */
    public function test_success(string $fixture, int $threshold, int $qtyFree, Expression $filterCriteria)
    {
        $order = $this->getOrderFixture($fixture);
        $instance = new BuyXGetYFree($threshold, $qtyFree, $filterCriteria);
        $result = $instance->apply($order);

        $this->assertCount(7, $result['items']);
        $this->assertEquals(0, \end($result['items'])['total']);

        $this->assertMatchesJsonSnapshot(\json_encode($result));
    }

    /**
     * @return array
     */
    public function orderWithItemsProvider()
    {
        return [
            ['expanded1', 2, 1, Expr::andX([
                new FromCategory(2),
                Expr::key('quantity', Expr::greaterThanEqual(2))
            ])]
        ];
    }
}
