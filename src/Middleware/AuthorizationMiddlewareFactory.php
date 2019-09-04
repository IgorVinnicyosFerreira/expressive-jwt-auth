<?php

declare(strict_types=1);

namespace ExpressiveJWTAuth\Middleware;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizationMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthorizationMiddleware
    {

        $authConfig = isset($container->get('config')['authConfig'])
            ? $container->get('config')['authConfig']
            : [];

        $urlHelper = $container->get(UrlHelper::class);
        return new AuthorizationMiddleware($authConfig, $urlHelper);
    }
}
