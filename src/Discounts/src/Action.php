<?php

namespace TeamLeader\Domain\Sales\Discounts;

/**
 * An action that can be performed on an order once a discount can be granted
 */
interface Action extends \JsonSerializable
{
    /**
     * @param array $order
     * @return array The modified order information
     */
    public function apply(array $order): array;
}
