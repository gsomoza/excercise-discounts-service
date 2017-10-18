<?php

namespace TeamLeader\Domain\Sales\Discounts\Action;

use TeamLeader\Domain\Sales\Discounts\Action;

/**
 * Grants a percentage discount over the entire order
 */
class OrderDiscountPercent implements Action
{
    /** @var float */
    private $amount;

    /**
     * OrderDiscountPercent constructor.
     * @param float $amount The percentage amount. For 50% use 0,5.
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param array $order
     * @return array The modified order information
     */
    public function apply(array $order): array
    {
        $oldTotal = \floatval($order['total']);
        $newTotal = \min($oldTotal - $oldTotal * $this->amount, 0);
        
        $order['total'] = \sprintf('%0.2f', $newTotal);

        return $order;
    }
}
