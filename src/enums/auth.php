<?php

namespace Surreal\enums;

enum auth: string
{
    case ROOT = 'root';
    case NAMESPACE = 'namespace';
    case DATABASE = 'database';
    case SCOPE = 'scope';
    case ANONYMOUS = 'anonymous';
}