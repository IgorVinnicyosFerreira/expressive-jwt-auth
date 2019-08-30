<?php

namespace WD7\Auth;

use Zend\ServiceManager\Factory\InvokableFactory;
use WD7\Auth\JWT\JWT;
use WD7\Auth\JWT\JWTFactory;
use WD7\Auth\Middleware\AuthenticationMiddleware;
use WD7\Auth\Middleware\AuthenticationMiddlewareFactory;
use WD7\Auth\Middleware\AuthorizationMiddleware;
use WD7\Auth\Middleware\AuthorizationMiddlewareFactory;

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
            'aliases' => [

            ],
            'factories' => [
                JWt::class => JWTFactory::class,
                AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
                AuthorizationMiddleware::class  =>  AuthorizationMiddlewareFactory::class
            ],
        ];
    }


    
}
