<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria\Items;

use Webmozart\Expression\Expr;
use Webmozart\Expression\Selector\Method;

/**
 * Checks whether the given order item is from a certain category
 */
final class OrderItemIsFromCategory extends Method
{
    public function __construct(int $categoryId)
    {
        parent::__construct('getProduct', [], Expr::key('category', Expr::equals($categoryId)));
    }
}
