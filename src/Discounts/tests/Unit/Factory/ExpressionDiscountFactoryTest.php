<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Factory;

use PHPUnit\Framework\TestCase;
use TeamLeader\Domain\Sales\Discounts\Action;
use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Discount;
use TeamLeader\Domain\Sales\Discounts\ExpressionDiscount;
use TeamLeader\Domain\Sales\Discounts\Factory\ActionBuilder;
use TeamLeader\Domain\Sales\Discounts\Factory\CriteriaBuilder;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\All;

/**
 * Class ExpressionDiscountFactoryTest
 */
class ExpressionDiscountFactoryTest extends TestCase
{
    /** @var ExpressionDiscountBuilder */
    private $factory;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->factory = new ExpressionDiscountBuilder();
    }

    /**
     * Tests that an ExpressionDiscount can be created successfully if minimal params are present and they're all valid
     * @return void
     */
    public function test_successful_discount_creation_with_minimal_params()
    {
        $result = $this->factory->build([
            'criteria' => new RevenueGreaterThan(123.45),
            'actions' => [$this->getMockBuilder(Action::class)->getMock()],
        ]);

        $this->assertIsExpressionDiscount($result);
    }

    /**
     * Tests that several invalid configurations produce appropriate exceptions
     *
     * @param array $config
     * @param string $exception
     * @return void
     * @dataProvider provideInvalidConfigurations
     */
    public function test_invalid_configurations_produce_catchable_exceptions(array $config, string $exception = null)
    {
        $this->expectException($exception ?? \InvalidArgumentException::class);
        $this->factory->build($config);
    }

    /**
     * @return array
     */
    public function provideInvalidConfigurations(): array
    {
        return [
            [ [] ], // empty configuration
            [ ['foo' => 'bar'] ], // configuration without 'filterCriteria'
            [ ['criteria' => []] ], // 'actions' missing
            [ ['actions' => []] ], // 'filterCriteria' missing
            [ ['criteria' => 'bar', 'actions' => []] ], // 'filterCriteria' not array
            [ ['actions' => 'bar', 'criteria' => []] ], // 'actions' not array
        ];
    }

    private function assertIsExpressionDiscount($object)
    {
        $this->assertInstanceOf(Discount::class, $object);
        $this->assertInstanceOf(ExpressionDiscount::class, $object);
    }
}
