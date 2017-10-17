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
    /** @var CriteriaBuilder */
    private $criteriaBuilder;

    /**
     * @param CriteriaBuilder $criteriaBuilder
     */
    public function __construct(CriteriaBuilder $criteriaBuilder = null)
    {
        if (null === $criteriaBuilder) {
            $criteriaBuilder = new CriteriaBuilder();
        }
        $this->criteriaBuilder = $criteriaBuilder;
    }

    public function newFromConfig(array $discountConfig): ExpressionDiscount
    {
        Assert::true(!empty($discountConfig['criteria']), 'Expected key "criteria" with a non-empty array as value');
        Assert::isArray($discountConfig['criteria']);

        /** @var Expression $criteria */
        $criteria = $this->criteriaBuilder->buildCriteria($discountConfig['criteria']);

        return new ExpressionDiscount($criteria);
    }
}
