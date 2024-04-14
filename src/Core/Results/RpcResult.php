<?php

namespace Surreal\Core\Results;

use Surreal\Core\AbstractSurreal;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\RpcResponse;

class RpcResult implements ResultInterface
{
    public static function from(ResponseInterface $response): mixed
    {
        //TODO: implement error rpc response.

        return match ($response::class) {
            RpcResponse::class => $response->result,
            default => null
        };
    }

    public static function requiredHTTPHeaders(AbstractSurreal $client): array
    {
        $base = [
            'Content-Type: application/cbor',
            'Accept: application/cbor',
            'Surreal-NS: ' . $client->getNamespace(),
            'Surreal-DB: ' . $client->getDatabase(),
        ];

        if(($scope = $client->auth->getScope()) !== null) {
            $base[] = 'Surreal-SC: ' . $scope;
        }

        if(($token = $client->auth->getToken()) !== null) {
            $base[] = 'Authorization: Bearer ' . $token;
        }

        return $base;
    }

}