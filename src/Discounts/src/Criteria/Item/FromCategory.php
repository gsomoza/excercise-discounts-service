<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria\Item;

use Webmozart\Expression\Expr;
use Webmozart\Expression\Selector\Key;
use Webmozart\Expression\Selector\Method;

/**
 * Checks whether the given order item is from a certain category
 */
final class FromCategory extends Key
{
    public function __construct(int $categoryId)
    {
        parent::__construct('product', Expr::key('category', Expr::equals($categoryId)));
    }
}
