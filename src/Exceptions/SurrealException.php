<?php

namespace Surreal\Exceptions;

use Exception;

class SurrealException extends Exception
{
	public function __construct(string $message)
	{
		parent::__construct("SurrealException: " . $message, 500);
	}
}