<?php

namespace TeamLeader\Domain\Sales\Discounts\Service;

/**
 * Grants discounts to a given order
 */
interface GrantsDiscounts
{
    /**
     * Apply discounts to order data and return the update data with discount information
     *
     * Normally (with more time) I'd prefer to actually model the order and discount data, then serialise the objects
     * to JSON (i.e. \JsonSerializable), but here I'm dealing directly with an array to make things simpler.
     *
     * @param array $order
     * @return array
     */
    public function grantOnOrder(array $order): array;
}
