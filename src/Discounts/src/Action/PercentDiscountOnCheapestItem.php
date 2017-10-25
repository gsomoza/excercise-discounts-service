<?php

namespace TeamLeader\Domain\Sales\Discounts\Action;

use TeamLeader\Domain\Sales\Discounts\Action;
use TeamLeader\Domain\Sales\Discounts\Percentage;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;

/**
 * Applies a predefined discount discount on the cheapest item in the given collection, after filtering it with
 * criteria.
 *
 * IMPORTANT: this action must be applied BEFORE any actions that apply discounts at the order level.
 */
class PercentDiscountOnCheapestItem implements Action
{
    /** @var Expression */
    private $filter;

    /** @var Percentage */
    private $discount;

    /**
     * PercentDiscountOnCheapestItem constructor.
     * @param Percentage $discount The discount percentage
     * @param Expression $filter A filter to allow considering only a sub-set of items
     */
    public function __construct(Percentage $discount, Expression $filter = null)
    {
        if (null === $filter) {
            $filter = Expr::true(); // check all items
        }
        $this->filter = $filter;
        $this->discount = $discount;
    }

    /**
     * @param array $order
     * @return array The modified order information
     */
    public function apply(array $order): array
    {
        $orderTotal = 0.00;
        $cheapest = null;
        $cheapestKey = null;
        foreach ($order['items'] as $key => $item) {
            $orderTotal += $item['total'];
            if ($this->filter->evaluate($item) // only consider items that match the filter
                && (null === $cheapest || $cheapest['total'] > $item['total'])
            ) {
                $cheapest = $item;
                $cheapestKey = $key;
            }
        }

        if (!$cheapest) {
            return $order;
        }

        $itemTotal = \floatval($cheapest['total']);
        $orderTotal -= $itemTotal; // remove it from the total because we're going to update it

        $discountedItemTotal = $this->discount->off($itemTotal);
        $orderTotal += $discountedItemTotal; // add it back in after updating

        $cheapest['total'] = \number_format($discountedItemTotal, 2);
        $cheapest['discounts'][] = \array_merge(
            $this->jsonSerialize(),
            ['discounted_amount' => \number_format($this->discount->of($itemTotal), 2)]
        );

        $order['items'][$cheapestKey] = $cheapest;

        // update the order total
        $order['total'] = (string) \number_format($orderTotal, 2);

        return $order;
    }

    /**
     * TODO: make this output configurable too
     */
    public function jsonSerialize()
    {
        return [
            'description' => $this->discount->toString() . ' discount on cheapest item in filtered set',
            'filter' => $this->filter->toString(),
        ];
    }
}
