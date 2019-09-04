<?php

namespace ExpressiveJWTAuth\JWT;

use Firebase\JWT\JWT as FirebaseJWT;
use Exception;
use Zend\Diactoros\Response\JsonResponse;

class JWT
{

    private $key;
    private $alg;
    private $exp;
    private $iss;

    const DEFAULT_ALG = "HS256";

    public function __construct(array $jwtConfig)
    {

        if (empty($jwtConfig))
            throw new Exception("Configurações do token não informadas");

        if (!isset($jwtConfig['key']))
            throw new Exception("Chave de encriptação não definida");

        $this->key = $jwtConfig['key'];

        if (isset($jwtConfig['alg'])) {
            if (!key_exists($this->alg, FirebaseJWT::$supported_algs))
                $this->alg = $jwtConfig['alg'];
        } else {
            $this->alg = self::DEFAULT_ALG;
        }

        if (isset($jwtConfig['exp'])) {
            if (!is_int($jwtConfig['exp']))
                throw new Exception("O tempo de expiração do token precisa ser especificado em numeros inteiros");

            $this->exp =  $jwtConfig['exp'];
        }

        if (isset($jwtConfig['iss']))
            $this->iss = $jwtConfig['iss'];
    }

    public function createToken($payload, array $permissions = null): string
    {
        if ($this->exp)
            $payload['exp'] = strtotime("now +{$this->exp} minutes");

        if ($this->iss)
            $payload['iss'] = $this->iss;

        if ($permissions)
            $payload['permissions'] = $permissions;

        $payload["iat"] = strtotime("now");


        return FirebaseJWT::encode($payload, $this->key, $this->alg);
    }

    public function getTokenPayload(string $token): object
    {

        return FirebaseJWT::decode($token, $this->key, [$this->alg]);
    }

    public function tokenIsValid($token): bool
    {

        try {
            $token = $this->getTokenPayload($token);

            if ($this->exp) {
                $tokenExpiration = $token->exp;
                $currentTime = strtotime('now');

                if ($tokenExpiration < $currentTime)
                    return false;
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }

    public function tokenResponse(string $token): JsonResponse
    {
        $jsonResponse = new JsonResponse([]);
        return $jsonResponse->withHeader("authorization", "Bearer {$token}");
    }
}
