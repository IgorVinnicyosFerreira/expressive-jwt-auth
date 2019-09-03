<?php

namespace ExpressiveJWTAuth;

use Zend\ServiceManager\Factory\InvokableFactory;
use ExpressiveJWTAuth\JWT\JWT;
use ExpressiveJWTAuth\JWT\JWTFactory;
use ExpressiveJWTAuth\Middleware\AuthenticationMiddleware;
use ExpressiveJWTAuth\Middleware\AuthenticationMiddlewareFactory;
use ExpressiveJWTAuth\Middleware\AuthorizationMiddleware;
use ExpressiveJWTAuth\Middleware\AuthorizationMiddlewareFactory;

class ConfigProvider
{
    /**
     * Return general-purpose zend-i18n configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'aliases' => [],
            'factories' => [
                JWt::class => JWTFactory::class,
                AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
                AuthorizationMiddleware::class  =>  AuthorizationMiddlewareFactory::class
            ],
        ];
    }
}
