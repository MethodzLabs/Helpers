<?php

namespace Methodz\Helpers\Curl\Exception;

class CurlResultCodeException extends \Exception
{
	public function __construct(int $http_code, int $code = 0, ?\Throwable $previous = null)
	{
		parent::__construct("Error during request curl $http_code", $code, $previous);
	}
}
