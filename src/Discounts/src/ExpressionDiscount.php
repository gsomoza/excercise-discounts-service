<?php

namespace TeamLeader\Domain\Sales\Discounts;

use TeamLeader\Domain\Sales\Discounts\Exception\DiscountDoesNotApplyToOrder;
use Webmozart\Expression\Expression;

/**
 * A discount that uses a \Webmozart\Expression to determine whether it can apply itself or not
 */
final class ExpressionDiscount implements Discount
{
    /** @var  Expression */
    private $expression;

    /**
     * ExpressionDiscount constructor.
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    /**
     * Returns whether or not the discount can apply to the order
     *
     * @param array $order
     * @return bool
     * TODO: instead of returning bool, return an object that represents either a "yes" or a reason wny not
     */
    public function canApplyToOrder(array $order): bool
    {
        return $this->expression->evaluate($order);
    }

    /**
     * Applies the discount to the order.
     *
     * @param array $order
     * @return array
     * @throws DiscountDoesNotApplyToOrder
     */
    public function applyToOrder(array $order): array
    {
        if (!$this->canApplyToOrder($order)) {
            throw new DiscountDoesNotApplyToOrder([$this->expression->toString()]);
        }

        $discounts = $order['discounts'] ?? [];
        $discounts[] = [

        ];
    }
}
