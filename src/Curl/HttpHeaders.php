<?php

namespace Surreal\Curl;

use Surreal\Core\AbstractSurreal;

class HttpHeaders
{
    private static function getBaseHeaders(AbstractSurreal $surreal): array
    {
        return [
            'Accept' => 'application/cbor',
            'Content-Type' => 'application/cbor'
        ];
    }

    public static function getRequiredAuthHeaders(AbstractSurreal $surreal): array
    {
        $base = self::getBaseHeaders($surreal);

        if(($scope = $surreal->auth->getScope()) !== null) {
            $base['Surreal-SC'] = $scope;
        }

        return [];
    }
}