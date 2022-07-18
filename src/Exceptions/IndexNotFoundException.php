<?php

namespace Methodz\Helpers\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;


/**
 * @author Thomas FONTAINE--TUFFERY
 */
class IndexNotFoundException extends Exception
{
	#[Pure] public function __construct(int|string $index, $code = 0, Throwable $previous = null)
	{
		parent::__construct("Index not found: $index", $code, $previous);
	}
}
