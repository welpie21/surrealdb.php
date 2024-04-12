<?php

namespace Surreal\Exceptions;

use Exception;

class SurrealForbiddenException extends Exception
{
	public function __construct(string $message)
	{
		parent::__construct("SurrealForbiddenException: " . $message, 403);
	}
}
