<?php

namespace TeamLeader\Domain\Sales\Discounts\Action;

use TeamLeader\Domain\Sales\Discounts\Action;
use TeamLeader\Domain\Sales\Discounts\Percentage;

/**
 * Grants a percentage discount over the entire order
 */
class OrderDiscountPercent implements Action
{
    /** @var Percentage */
    private $discount;

    /**
     * OrderDiscountPercent constructor.
     * @param Percentage $discount The discount percentage to apply
     */
    public function __construct(Percentage $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @param array $order
     * @return array The modified order information
     */
    public function apply(array $order): array
    {
        $oldTotal = \floatval($order['total']);
        $newTotal = $this->discount->off($oldTotal);

        $order['total'] = \number_format($newTotal, 2);

        return $order;
    }

    /**
     * TODO: make this output configurable too
     */
    public function jsonSerialize()
    {
        return [
            'description' => 'Discount of ' . $this->discount->toString() . ' on entire order',
        ];
    }
}
