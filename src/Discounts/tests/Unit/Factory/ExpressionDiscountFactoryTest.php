<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Factory;

use PHPUnit\Framework\TestCase;
use TeamLeader\Domain\Sales\Discounts\Criteria\Customer\RevenueGreaterThan;
use TeamLeader\Domain\Sales\Discounts\Discount;
use TeamLeader\Domain\Sales\Discounts\ExpressionDiscount;
use TeamLeader\Domain\Sales\Discounts\Factory\ExpressionDiscountBuilder;
use Webmozart\Expression\Constraint\GreaterThan;
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
        $result = $this->factory->newFromConfig([
            'criteria' => [
                'class' => RevenueGreaterThan::class,
                'params' => [123.45],
            ],
        ]);

        $this->assertIsExpressionDiscount($result);
    }

    /**
     * Tests that an ExpressionDiscount can be created successfully with criteria that has a sub-criteria as a parameter
     * @return void
     */
    public function test_successful_creation_with_recursive_criteria()
    {
        $result = $this->factory->newFromConfig([
            'criteria' => [
                'class' => All::class,
                'params' => [
                    [
                        'class' => GreaterThan::class,
                        'params' => [3],
                    ],
                ],
            ],
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
        $this->factory->newFromConfig($config);
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
            [
                ['criteria' => [ // 'criteria' without 'class'
                    'foo' => 'bar',
                ]],
            ],
            [
                ['criteria' => [ // 'criteria' with empty 'class'
                    'class' => '',
                ]],
            ],
            [
                ['criteria' => [ // 'criteria' with invalid class
                    'class' => '\\DoesntExist',
                ]],
            ],
        ];
    }

    private function assertIsExpressionDiscount($object)
    {
        $this->assertInstanceOf(Discount::class, $object);
        $this->assertInstanceOf(ExpressionDiscount::class, $object);
    }
}
