<?php

namespace TeamLeader\Domain\Sales\Discounts\Factory;

use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;

/**
 * Builds Criteria based on a predefined config structure.
 */
final class CriteriaBuilder
{
    /**
     * @param array $criteriaConfig
     * @return Expression
     */
    public function buildCriteria(array $criteriaConfig): Expression
    {
        Assert::keyExists($criteriaConfig, 'class');
        $criteriaClass = $criteriaConfig['class'];
        Assert::classExists($criteriaClass);

        $params = \array_map(
            function($param) {
                // if one of the params is another criteria, then recurse; otherwise return as-is
                return \is_array($param) && !empty($param['class']) ? $this->buildCriteria($param) : $param;
            },
            $criteriaConfig['params'] ?? []
        );

        /** @var Expression $criteria */
        return new $criteriaClass(...$params);
    }
}
