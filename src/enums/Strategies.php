<?php

namespace Surreal\enums;

enum Strategies: string
{
    case HTTP = 'http';
    case WEBSOCKET = 'websocket';
}