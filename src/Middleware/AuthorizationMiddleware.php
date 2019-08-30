<?php

namespace WD7\Auth\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Helper\UrlHelper;

class AuthorizationMiddleware implements MiddlewareInterface
{
    private $authConfig;
    private $urlHelper;

    public function __construct(array $authConfig, UrlHelper $urlHelper)
    {
        $this->authConfig = $authConfig;
        $this->urlHelper = $urlHelper;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $routeName = $this->urlHelper->getRouteResult()->getMatchedRouteName();

            if (isset($this->authConfig['ignoredRoutes'])) {
                if (in_array($routeName, $this->authConfig['ignoredRoutes']))
                    return $handler->handle($request);
            }

            $tokenPayload = $request->getAttribute("token_payload", []);

            if (!in_array($routeName, $tokenPayload->permissions))
                throw new Exception("Usuário não autorizado");

            return $handler->handle($request);
        } catch (Exception $error) {
            return new JsonResponse(["error" => $error->getMessage()], 403);
        }
    }
}
