<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria;

use Webmozart\Expression\Expression;
use Webmozart\Expression\Selector\Key;

/**
 * Selects the items in an order
 */
class OrderItems extends Key
{
    public function __construct(Expression $expr)
    {
        parent::__construct('items', $expr);
    }
}
