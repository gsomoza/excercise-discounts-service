<?php

namespace TeamLeader\Domain\Sales\Discounts\Exception;

use Throwable;

/**
 * Class DiscountDoesNotApplyToOrder
 */
final class DiscountDoesNotApplyToOrder extends \Exception
{
    /** @var string[] */
    private $reasons;

    public function __construct(array $reasons, Throwable $previous = null)
    {
        parent::__construct(
            'Discount cannot be applied to order.', // TODO: be more specific (discount id, order id)
            1001,
            $previous
        );
    }

    public static function because(array $reasons, Throwable $previous = null): DiscountDoesNotApplyToOrder
    {
        return new static($reasons, $previous);
    }
}
