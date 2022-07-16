<?php

namespace Methodz\Helpers\Exceptions;

use Exception;
use Throwable;

class NotBooleanException extends Exception
{
	public function __construct(string $value, int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct("Variable $value is not a boolean", $code, $previous);
	}
}
