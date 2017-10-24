<?php

namespace TeamLeader\Domain\Sales\Discounts\Service;

use TeamLeader\Domain\Sales\Discounts\Discount;
use TeamLeader\Domain\Sales\Discounts\Exception\CantApplyDiscountToOrder;
use Webmozart\Assert\Assert;
use Webmozart\Expression\Expr;

/**
 * Grants discounts to a given order
 */
final class DiscountGranterService implements GrantsDiscounts
{
    /** @var Discount[] */
    private $discounts;

    /**
     * @param Discount[] $discounts
     */
    public function __construct(array $discounts)
    {
        Assert::allIsInstanceOf($discounts, Discount::class);
        $this->discounts = $discounts;
    }

    /**
     * Apply discounts to order data and return the updated data, including detailed discount information
     *
     * Normally (with more time) I'd prefer to actually model the order and discount data, then serialise the objects
     * to JSON (i.e. \JsonSerializable), but here I'm dealing directly with an array to make things simpler.
     *
     * @param array $order
     * @return array
     */
    public function grantOnOrder(array $order): array
    {
        /** @var Discount[] $applicable */
        $applicable = Expr::filter(
            $this->discounts,
            Expr::method('canApplyToOrder', $order, Expr::equals(true))
        );

        foreach ($applicable as $discount) {
            try {
                $order = $discount->applyToOrder($order);
            } catch (CantApplyDiscountToOrder $e) {
                // this should never happen thanks to the filter at the beginning of the method
                // currently not handled for simplicity:
                // 1) $this->log->warning(....); // something very iffy is going on!
                // 2) decide whether to continue, show a message, etc.
            }
        }

        return $order;
    }
}
