<?php

namespace TeamLeader\CustomerApi;

use TeamLeader\CustomerApi\Action;

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
                    Action\GetCustomerAction::class,
                ],
            ],
        ];
    }
}
