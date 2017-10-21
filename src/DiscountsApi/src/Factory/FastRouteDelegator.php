<?php

namespace TeamLeader\DiscountsApi\Factory;

use TeamLeader\DiscountsApi\Action;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * Class FastRouteDelegator
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class FastRouteDelegator implements DelegatorFactoryInterface
{

    /**
     * A factory that creates delegates of a given service
     *
     * @param  ContainerInterface $container
     * @param  string $name
     * @param  callable $callback
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /** @var \Zend\Expressive\Application $app */
        $app = $callback();

        $app->route('/api/discounts/grant', Action\GrantAction::class, ['POST']);

        return $app;
    }
}
