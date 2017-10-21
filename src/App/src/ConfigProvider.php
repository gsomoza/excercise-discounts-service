<?php

namespace App;

use App\Action;
use App\Factory;
use App\Factory\ApiClientFactory;
use Zend\ProblemDetails\ProblemDetailsMiddleware;
use Zend\ProblemDetails\ProblemDetailsMiddlewareFactory;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

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
                'factories' => [
                    ApiClient::class => ApiClientFactory::class,
                ],
                'delegators' => [
                    \Zend\Expressive\Application::class => [
                        Factory\PipelineAndRoutesDelegator::class,
                    ],
                ],
            ],
            ApiClient::class => [
                // override in config/autoload/local.php
            ],
        ];
    }
}
