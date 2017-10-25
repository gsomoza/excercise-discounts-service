<?php

namespace TeamLeader\Domain\Sales\Discounts\Action;

use TeamLeader\Domain\Sales\Discounts\Action;
use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;

/**
 * Buy X items, get Y for free. For simplicity, this assumes that:
 *      1. The order items have been "compacted" so that there aren't multiple line items for the same item
 *      2. If the user bought more than the threshold, the extra items will NOT be converted into free items, and free
 *         items will still be added.
 */
class BuyXGetYFree implements Action
{
    /** @var int */
    private $threshold;

    /** @var int */
    private $qtyFree;

    /** @var Expression */
    private $filterCriteria;

    /**
     * BuyXGetYFree constructor.
     * @param int $threshold
     * @param int $qtyFree
     * @param Expression $filterCriteria
     */
    public function __construct(int $threshold, int $qtyFree, Expression $filterCriteria)
    {
        $this->threshold = $threshold;
        $this->qtyFree = $qtyFree;
        $this->filterCriteria = $filterCriteria;
    }

    /**
     * @param array $order
     * @return array The modified order information
     */
    public function apply(array $order): array
    {
        $toProcess = Expr::filter($order['items'], $this->filterCriteria);
        foreach ($toProcess as $item) {
            $qty = $item['quantity'];
            $toAdd = \floor($qty / $this->threshold) * $this->qtyFree;
            if ($toAdd > 0) {
                $unitPrice = \floatval($item['unit-price']);
                // make sure we don't grant a negative discount (that's a charge!)
                $discount = \max($unitPrice, 0) * $toAdd;

                $order['items'][] = [ // new line items with the free products
                   'product-id' => $item['product-id'],
                   'quantity' => "$toAdd",
                   'unit-price' => \number_format($unitPrice, 2),
                   'total' => '0.00',
                   'discounts' => [
                       \array_merge(
                           $this->jsonSerialize(),
                           ['discounted_amount' => \number_format($discount, 2)]
                       ),
                   ],
                ];
            }
        }

        return $order;
    }

    /**
     * TODO: make this output configurable too
     */
    public function jsonSerialize()
    {
        return [
            'description' => 'Buy ' . $this->threshold . ' get ' . $this->qtyFree . ' free.',
        ];
    }
}
