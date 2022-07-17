<?php

namespace Exceptions;

use Exception;
use Throwable;

class NotNullException extends Exception
{
	public function __construct(string $value, int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct("Variable $value is not null", $code, $previous);
	}
}
