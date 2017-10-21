<?php

namespace TeamLeader\Domain\Sales\Discounts\Factory;

use Psr\Container\ContainerInterface;
use TeamLeader\Domain\Sales\Discounts\Discount;
use TeamLeader\Domain\Sales\Discounts\Service\DiscountGranterService;
use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;
use Zend\ServiceManager\ServiceManager;

/**
 * Creates discounts from config to build DiscountGranterService
 */
final class DiscountGranterFactory
{
    /**
     * @param ServiceManager $container
     * @return DiscountGranterService
     */
    public function __invoke(ServiceManager $container)
    {
        $config = $container->get('config');

        // source for this could also be a database, etc
        $discountsConfig = $config['teamleader']['domain']['sales']['discounts']['enabled'] ?? [];

        // in the future other types of discounts could be supported, but right now it's only this
        /** @var ExpressionDiscountBuilder $expressionDiscountBuilder */
        $expressionDiscountBuilder = $container->get(ExpressionDiscountBuilder::class);

        /** @var Discount[] $discounts */
        $discounts = \array_map(
            function ($discountConfig) use ($expressionDiscountBuilder) {
                return $expressionDiscountBuilder->build($discountConfig);
            },
            $discountsConfig
        );

        return new DiscountGranterService($discounts);
    }
}
