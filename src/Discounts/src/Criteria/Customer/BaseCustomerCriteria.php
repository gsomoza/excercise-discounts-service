<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria\Customer;

use TeamLeader\Domain\Sales\Discounts\Criteria\HumanReadableExpression;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\Method;

/**
 * Base criteria for customers
 */
abstract class BaseCustomerCriteria extends Method implements HumanReadableExpression
{
    public function __construct(array $arguments, Expression $expr)
    {
        parent::__construct('getCustomer', $arguments, $expr);
    }
}
