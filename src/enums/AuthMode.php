<?php

namespace Surreal\enums;

enum AuthMode: string
{
    case ROOT = "root";
    case DATABASE = "database";
    case SCOPE = "scope";
}