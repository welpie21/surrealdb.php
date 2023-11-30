<?php

namespace Surreal\Enums\Auth;

enum AuthMethod: string
{
    case ROOT = 'root';
    case NAMESPACE = 'namespace';
    case DATABASE = 'database';
    case SCOPE = 'scope';
    case ANONYMOUS = 'anonymous';
}