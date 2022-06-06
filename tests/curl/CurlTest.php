<?php

namespace curl;

use Methodz\Helpers\Curl\Curl;
use Methodz\Helpers\Curl\CurlInfoKeyEnum;
use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{

	public function testGet()
	{
		$http_code = Curl::init("station.zaacom.fr")
			->exec()
			->getInfo(CurlInfoKeyEnum::HTTP_CODE);
		self::assertTrue(is_int($http_code));
	}
}
