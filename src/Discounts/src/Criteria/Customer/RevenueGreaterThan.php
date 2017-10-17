<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria\Customer;

use Webmozart\Expression\Expr;

/**
 * Customer revenue is greater than or equal to given value
 */
final class RevenueGreaterThan extends BaseCustomerCriteria
{
    /** @var float */
    private $value;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
        parent::__construct([], Expr::key('revenue', Expr::greaterThan($value)));
    }

    /**
     * A human-readable representation of this criteria
     *
     * @return string
     */
    public function toHumanString(): string
    {
        return \sprintf("Customer's revenue must be greater than %0.2f", $this->value);
    }
}
