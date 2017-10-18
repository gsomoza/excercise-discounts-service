<?php

namespace TeamLeader\Domain\Sales\Discounts;

use TeamLeader\Domain\Sales\Discounts\Exception\CantApplyDiscountToOrder;

/**
 * Represents a discount
 */
interface Discount
{
    /**
     * Returns whether or not the discount can apply to the order
     *
     * @param array $order
     * @return bool
     * TODO: instead of returning bool, return an object that represents either a "yes" or a number of reasons wny not
     */
    public function canApplyToOrder(array $order): bool;

    /**
     * Applies the discount to the order.
     *
     * @param array $order
     * @return array
     * @throws CantApplyDiscountToOrder
     */
    public function applyToOrder(array $order): array;
}
