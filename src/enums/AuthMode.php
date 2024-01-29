<?php

namespace Surreal\enums;

enum AuthMode: string
{
    case ROOT = 'root';
    case NAMESPACE = 'namespace';
    case DATABASE = 'database';
    case SCOPE = 'scope';
    case ANONYMOUS = 'anonymous';
}