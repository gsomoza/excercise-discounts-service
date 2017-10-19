<?php

namespace TeamLeader\Domain\Sales\Discounts\Factory;

use TeamLeader\Domain\Sales\Discounts\ExpressionDiscount;
use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;

/**
 * Builds expression discounts based on a predefined config structure. Not meant to be used as a container factory
 */
final class ExpressionDiscountBuilder
{
    public function build(array $config): ExpressionDiscount
    {
        Assert::keyExists($config, 'criteria');
        Assert::isInstanceOf($config['criteria'], Expression::class);

        Assert::true(!empty($config['actions']), 'Expected key "actions" with a non-empty array as value');
        Assert::isArray($config['actions']);

        return new ExpressionDiscount($config['criteria'], $config['actions']);
    }
}
