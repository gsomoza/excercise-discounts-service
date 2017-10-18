<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Action;

use PHPUnit\Framework\TestCase;
use TeamLeader\Domain\Sales\Discounts\Action\BuyXGetYFree;
use TeamLeader\Domain\Sales\Discounts\Criteria\Item\FromCategory;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;

/**
 * Class BuyXGetYFreeTest
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class BuyXGetYFreeTest extends TestCase
{
    /**
     * @param int $threshold
     * @param int $qtyFree
     * @param Expression $filterCriteria
     * @return void
     * @dataProvider orderWithItemsProvider
     */
    public function test_success(string $fixture, int $threshold, int $qtyFree, Expression $filterCriteria)
    {
        $order = \json_decode(\file_get_contents(__DIR__."/../../fixtures/order/$fixture.json"), true);
        $instance = new BuyXGetYFree($threshold, $qtyFree, $filterCriteria);
        $result = $instance->apply($order);

        $this->assertCount(7, $result['items']);
        $this->assertEquals(0, \end($result['items'])['total']);
        // TODO: maybe some more checks
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
