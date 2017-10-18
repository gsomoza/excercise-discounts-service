<?php

namespace TeamLeader\Domain\Sales\Discounts;

use TeamLeader\Domain\Sales\Discounts\Exception\CantApplyDiscountToOrder;
use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;

/**
 * A discount that uses a \Webmozart\Expression to determine whether it can apply itself or not
 */
final class ExpressionDiscount implements Discount
{
    /** @var  Expression */
    private $expression;

    /** @var Action[] */
    private $actions;

    /** @var string */
    private $name;

    /**
     * ExpressionDiscount constructor.
     * @param Expression $expression
     * @param array $actions
     * @param string|null $name
     */
    public function __construct(Expression $expression, array $actions, string $name = null)
    {
        Assert::allIsInstanceOf($actions, Action::class);
        $this->actions = $actions;

        $this->expression = $expression;

        if (null === $name) {
            $name = $this->expression->toString();
        }
        $this->name = $name;
    }

    /**
     * Returns whether or not the discount can apply to the order
     *
     * @param array $order
     * @return bool
     * TODO: instead of returning bool, return an object that represents either a "yes" or a reason wny not
     */
    public function canApplyToOrder(array $order): bool
    {
        return $this->expression->evaluate($order);
    }

    /**
     * Applies the discount to the order.
     *
     * @param array $order
     * @return array
     * @throws CantApplyDiscountToOrder
     */
    public function applyToOrder(array $order): array
    {
        if (!$this->canApplyToOrder($order)) {
            throw new CantApplyDiscountToOrder($this->name, $order['id']);
        }

        foreach ($this->actions as $action) {
            $order = $action->apply($order);
        }

        $discounts = $order['applied_discounts'] ?? [];
        $discounts[] = [
            'name' => $this->name,
            'granted' => (new \DateTime())->format('c'),
        ];
        $order['applied_discounts'] = $discounts;

        return $order;
    }
}
