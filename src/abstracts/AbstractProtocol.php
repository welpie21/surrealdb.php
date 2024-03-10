<?php

namespace Surreal\abstracts;

use Surreal\interface\ClosableInterface;
use Surreal\interface\TimeoutInterface;

abstract class AbstractProtocol extends AbstractSurreal implements ClosableInterface, TimeoutInterface
{

}