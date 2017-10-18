<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria\Customer;

use TeamLeader\Domain\Sales\Discounts\Criteria\HumanReadableExpression;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\Key;
use Webmozart\Expression\Selector\Method;

/**
 * Base filterCriteria for customers
 */
abstract class BaseCustomerCriteria extends Key
{
    public function __construct(Expression $expr)
    {
        parent::__construct('customer', $expr);
    }
}
