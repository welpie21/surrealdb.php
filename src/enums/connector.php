<?php

namespace Surreal\Enums\Connector;

enum Connector: string
{
    case HTTP = 'http';
    case HTTPS = 'https';
    case WS = 'ws';
    case WSS = 'wss';
}
