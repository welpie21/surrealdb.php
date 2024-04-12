<?php

namespace Surreal\abstracts;

abstract class AbstractProtocol extends AbstractSurreal
{
    public function getAuthHeaders(): array
    {
        $headers = [];

        if (($token = $this->auth->getToken()) !== null) {
            $headers[] = "Authorization: Bearer " . $token;
        }

        if (($scope = $this->auth->getScope()) !== null) {
            $headers[] = $scope;
        }

        return $headers;
    }
}