<?php

namespace App;

use App\Action;
use App\Factory;

/**
 * Class ConfigProvider
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    Action\HomeAction::class,
                    Action\PingAction::class,
                ],
                'delegators' => [
                    \Zend\Expressive\Application::class => [
                        Factory\PipelineAndRoutesDelegator::class,
                    ],
                ],
            ],
        ];
    }
}
