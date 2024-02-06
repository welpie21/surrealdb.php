<?php

namespace Surreal\interfaces;

interface CBORInterface
{
    /**
     * The content type for CBOR headers.
     * @var string
     */
    const string CBOR_CONTENT_TYPE = "application/cbor";

    /**
     * The accept header for CBOR.
     * @var string
     */
    const string CBOR_ACCEPT = "Accept: " . self::CBOR_CONTENT_TYPE;
}