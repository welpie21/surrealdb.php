<?php

namespace Surreal\connectors;

use Surreal\abstracts\SurrealConnector;;
use const Surreal\constants\CURL_WITH_BODY;
use const Surreal\constants\CURL_BOTH_OPTIONS;

final readonly class HTTPConnector extends SurrealConnector
{
    /**
     * Returns health data of the database
     */
    public function status(): int
    {
        $ch = $this->getHandler("status");

        curl_setopt_array($ch, CURL_WITH_BODY);
        curl_exec($ch);

        $response_info = curl_getinfo($ch, CURLINFO_HTTP_CODE );

        curl_close($ch);

        return (int)$response_info;
    }

    public function health(): int
    {
        $ch = $this->getHandler("health");

        curl_setopt_array($ch, CURL_WITH_BODY);
        curl_exec($ch);

        $response_info = curl_getinfo($ch, CURLINFO_HTTP_CODE );

        curl_close($ch);

        return (int)$response_info;
    }

    function version(): string
    {
        $ch = $this->getHandler("version");

        curl_setopt_array($ch, CURL_WITH_BODY);
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    function export(): string
    {
        $ch = $this->getHandler("export");

        curl_setopt_array($ch, CURL_BOTH_OPTIONS);

        $response = curl_exec($ch);

        curl_close($ch);

        print($response);

        return $response;
    }
}