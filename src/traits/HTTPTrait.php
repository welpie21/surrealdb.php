<?php

namespace Surreal\traits;

use CurlHandle;

trait HTTPTrait
{
    /**
     * @param CurlHandle $handle
     * @return int
     */
    public function getCurlResponseCode(CurlHandle $handle): int
    {
        return curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    }

    /**
     * @param CurlHandle $handle
     * @return string
     */
    public function getCurlResponse(CurlHandle $handle): string
    {
        return curl_multi_getcontent($handle);
    }
}