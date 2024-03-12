<?php

namespace Surreal\enums;

enum CBORTag: int
{
    const DATE = 0;
    const UNDEFINED = 6;
    const UUID = 7;
    const DECIMAL = 8;
    const DURATION = 9;
    const RECORD_ID = 10;
}