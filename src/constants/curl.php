<?php

namespace Surreal\constants;

const CURL_WITH_HEADER = [
    CURLOPT_TIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => true
];

const CURL_WITH_BODY = [
    CURLOPT_TIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => false,
    CURLOPT_NOBODY => false
];

const CURL_BOTH_OPTIONS = [
    CURLOPT_TIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => false
];
