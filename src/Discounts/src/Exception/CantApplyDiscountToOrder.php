<?php

namespace TeamLeader\Domain\Sales\Discounts\Exception;

use Throwable;

/**
 * Class DiscountDoesNotApplyToOrder
 */
final class CantApplyDiscountToOrder extends \Exception
{
    /** @var string[] */
    private $reasons;

    public function __construct(string $name, string $orderId, Throwable $previous = null)
    {
        parent::__construct(
            'Discount "'.$name.'" cannot be applied to order "'.$orderId.'".',
            1001,
            $previous
        );
    }

    public static function because(array $reasons, Throwable $previous = null): CantApplyDiscountToOrder
    {
        return new static($reasons, $previous);
    }
}
