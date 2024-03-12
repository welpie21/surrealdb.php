<?php

namespace Surreal\enums;

enum HTTPCode: int
{
    case OK = 200;
    case ERR = 400;
    case FORBIDDEN = 403;
}