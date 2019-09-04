<?php

namespace ExpressiveJWTAuth\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ExpressiveJWTAuth\JWT\JWT;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Helper\UrlHelper;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private $authConfig;
    private $urlHelper;
    private $JWT;

    public function __construct(array $authConfig, UrlHelper $urlHelper, JWT $JWT)
    {
        $this->authConfig = $authConfig;
        $this->urlHelper = $urlHelper;
        $this->JWT = $JWT;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {

            $routeName = $this->urlHelper->getRouteResult()->getMatchedRouteName();

            if (isset($this->authConfig['ignoredRoutes'])) {
                if (in_array($routeName, $this->authConfig['ignoredRoutes']))
                    return $handler->handle($request);
            }

            $token = $this->getToken($request);

            if (!$this->JWT->tokenIsValid($token))
                throw new Exception("Token inválido");

            $payload = $this->JWT->getTokenPayload($token);

            $response = $handler->handle($request->withAttribute("token_payload", $payload));

            //novo token com expiração atualizada
            if (isset($this->authConfig['jwt']['expirationTime'])) {
                $newToken = $this->JWT->createToken((array) $payload);
                $response = $response->withHeader("autorizathion", "Bearer {$newToken}");
            }

            return $response;
        } catch (Exception $error) {
            return new JsonResponse(["error" => $error->getMessage()], 401);
        }
    }

    private function getToken(ServerRequestInterface $request): string
    {
        $authorizationHeader = $request->getHeader('authorization');
        if (!$authorizationHeader)
            throw new Exception("Authorization header não informado");
        $token = str_replace("Bearer ", "", $authorizationHeader[0]);
        return $token ?? "";
    }
}
