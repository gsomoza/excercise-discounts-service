<?php

namespace TeamLeader\Domain\Sales\Discounts\Factory;

use Psr\Container\ContainerInterface;
use TeamLeader\Domain\Sales\Discounts\Discount;
use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;

/**
 * Creates discounts from config to build DiscountGranterService
 */
final class DiscountGranterServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return Discount[]
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        // source for this could also be a database, etc
        $discountsConfig = $config['teamleader']['domain']['sales']['discounts']['enabled'] ?? [];

        // in the future other types of discounts could be supported, but right now it's only this
        $expressionDiscountFactory = $container->get(ExpressionDiscountBuilder::class);

        /** @var Discount[] $discounts */
        $discounts = \array_map(
            function ($discountConfig) use ($expressionDiscountFactory) {
                return $expressionDiscountFactory($discountConfig);
            },
            $discountsConfig
        );

        return $discounts;
    }
}
