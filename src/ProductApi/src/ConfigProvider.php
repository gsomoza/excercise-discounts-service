<?php

namespace TeamLeader\ProductApi;

use TeamLeader\ProductApi\Action;

/**
 * Config Provider for container
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    Action\ListProductsAction::class,
                ],
            ],
        ];
    }
}
