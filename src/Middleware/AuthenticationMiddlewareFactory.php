<?php

namespace ExpressiveJWTAuth\Middleware;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ExpressiveJWTAuth\JWT\JWT;
use Zend\Expressive\Helper\UrlHelper;

class AuthenticationMiddlewareFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthenticationMiddleware
    {
        $authConfig = isset($container->get('config')['authConfig'])
            ? $container->get('config')['authConfig']
            : [];

        $urlHelper = $container->get(UrlHelper::class);
        $JWT = $container->get(JWT::class);

        return new AuthenticationMiddleware($authConfig, $urlHelper, $JWT);
    }
}
