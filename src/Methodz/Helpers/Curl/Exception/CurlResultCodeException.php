<?php

namespace Methodz\Helpers\Curl\Exception;

use Exception;
use Throwable;

class CurlResultCodeException extends Exception
{
	public function __construct(int $http_code, string $addedMessage = "", int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct("Error during request curl $http_code" . ($addedMessage !== "" ? " $addedMessage" : $addedMessage), $code, $previous);
	}
}
