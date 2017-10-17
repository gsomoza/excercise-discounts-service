<?php

namespace TeamLeader\Domain\Sales\Discounts\Criteria;

/**
 * A "HumanReadableExpression" is the same as an Expression, only that it provides human-readable reasons
 */
interface HumanReadableExpression
{
    /**
     * A human-readable representation of this criteria
     * @return string
     */
    public function toHumanString(): string;
}
