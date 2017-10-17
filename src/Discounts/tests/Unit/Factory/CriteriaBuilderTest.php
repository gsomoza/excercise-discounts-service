<?php

namespace TeamLeaderTests\Domain\Sales\Discounts\Unit\Factory;

use PHPUnit\Framework\TestCase;
use TeamLeader\Domain\Sales\Discounts\Factory\CriteriaBuilder;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\All;

/**
 * Class ExpressionDiscountBuilderTest
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class CriteriaBuilderTest extends TestCase
{
    /** @var CriteriaBuilder */
    private $builder;

    public function setUp()
    {
        $this->builder = new CriteriaBuilder();
    }

    /**
     * Tests that it can build expressions recursively
     * @return void
     */
    public function test_successful_creation_with_recursive_criteria()
    {
        $expression = $this->builder->buildCriteria([
            'class' => All::class,
            'params' => [
                [
                    'class' => GreaterThan::class,
                    'params' => [3],
                ],
            ],
        ]);

        $this->assertInstanceOf(Expression::class, $expression);
        $this->assertTrue($expression->evaluate([4, 5, 6]));
        $this->assertFalse($expression->evaluate([1, 2, 3]));
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
        $this->builder->buildCriteria($config);
    }

    /**
     * @return array
     */
    public function provideInvalidConfigurations(): array
    {
        return [
            [
                [ // 'criteria' without 'class'
                    'foo' => 'bar',
                ],
            ],
            [
                [ // 'criteria' with empty 'class'
                    'class' => '',
                ],
            ],
            [
                [ // 'criteria' with invalid class
                    'class' => '\\DoesntExist',
                ],
            ],
        ];
    }
}
