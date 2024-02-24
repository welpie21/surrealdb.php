<?php

enum Opcode: int
{
    case CONTINUATION = 0;
    case TEXT = 1;
    case BINARY = 2;
    case CLOSE = 8;
    case PING = 9;
    case PONG = 10;
}