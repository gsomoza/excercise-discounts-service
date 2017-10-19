<?php

namespace TeamLeader\Domain\Sales\Discounts;

use Webmozart\Assert\Assert;

/**
 * Represents a percentage
 *
 * TODO: this value object could move to a more generic library
 */
class Percentage
{
    /** @var float */
    private $fraction;

    /**
     * @param float $percentage The percentage discount (e.g. 20 for 20%)
     */
    public function __construct(float $percentage)
    {
        Assert::lessThanEq($percentage, 100);
        Assert::greaterThanEq($percentage, 0);

        $this->fraction = $percentage / 100.0;
    }

    /**
     * Calculates the percentage off a given number (e.g. 10% off 20 = 18). Not to be mistaken with of()
     *
     * @param float $amount
     * @return float
     */
    public function off(float $amount): float
    {
        return $amount - $this->of($amount);
    }

    /**
     * Calculates the percentage of a number (e.g. 10% on 20 = 2). Not to be mistaken with off()
     * @param float $amount
     * @return float
     */
    public function of(float $amount): float
    {
        return $amount * $this->fraction;
    }

    /**
     * The percente as a human-readable string
     * @return string
     */
    public function toString(): string
    {
        return \sprintf('%0.2f%%', $this->fraction * 100);
    }
}
