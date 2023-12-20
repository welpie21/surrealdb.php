<?php

namespace Surreal\enums;

enum connector: string
{
    case HTTP = 'http';
    case HTTPS = 'https';
    case WS = 'ws';
    case WSS = 'wss';
}
