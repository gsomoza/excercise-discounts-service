<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Factory;

use PHPUnit\Framework\TestCase;
use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Discount;
use TeamLeader\Domain\Sales\Discounts\ExpressionDiscount;
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

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $criteriaBuilderMock;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->criteriaBuilderMock = $this->getMockBuilder(CriteriaBuilder::class)->getMock();
        $this->factory = new ExpressionDiscountBuilder($this->criteriaBuilderMock);
    }

    /**
     * Tests that an ExpressionDiscount can be created successfully if minimal params are present and they're all valid
     * @return void
     */
    public function test_successful_discount_creation_with_minimal_params()
    {
        $criteriaConfig = [
            'class' => RevenueGreaterThan::class,
            'params' => [123.45],
        ];
        $this->criteriaBuilderMock
            ->expects($this->once())
            ->method('buildCriteria')
            ->with($criteriaConfig)
            ->willReturn($this->getMockBuilder(Expression::class)->getMock());
        $result = $this->factory->newFromConfig([
            'criteria' => $criteriaConfig,
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
        (new ExpressionDiscountBuilder())->newFromConfig($config);
    }

    /**
     * @return array
     */
    public function provideInvalidConfigurations(): array
    {
        return [
            [ [] ], // empty configuration
            [ ['foo' => 'bar'] ], // configuration without 'criteria'
            [ ['criteria' => 'bar'] ], // 'criteria' not array
        ];
    }

    private function assertIsExpressionDiscount($object)
    {
        $this->assertInstanceOf(Discount::class, $object);
        $this->assertInstanceOf(ExpressionDiscount::class, $object);
    }
}
