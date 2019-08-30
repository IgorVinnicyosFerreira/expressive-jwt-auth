<?php

namespace WD7\Auth\JWT;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class JWTFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): JWT
    {
        $jwtConfig = isset($container->get('config')['authConfig'])
            ? $container->get('config')['authConfig']['jwt']
            : [];
            
        return new JWT($jwtConfig);
    }
}
