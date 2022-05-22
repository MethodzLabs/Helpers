<?php

namespace curl;

use Methodz\Helpers\Curl\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
	/**
	 * @dataProvider dataProviderBuild
	 */
	public function testBuild(string $url, string $excepted)
	{
		self::assertEquals($excepted, Url::from($url)->build());
	}

	public function dataProviderBuild(): array
	{
		return [
			[
				"https://username:password@hostname:9090/path?arg=value&arg2=value2#anchor",
				"https://username:password@hostname:9090/path?arg=value&arg2=value2#anchor",
			],
			[
				"http://username:password@hostname:9090/path?arg=value&arg2=value2#anchor",
				"http://username:password@hostname:9090/path?arg=value&arg2=value2#anchor",
			],
			[
				"https://username:password@hostname:9090/path?arg=value&arg2=value2#",
				"https://username:password@hostname:9090/path?arg=value&arg2=value2",
			],
			[
				"https://username:password@hostname:9090/path?#anchor",
				"https://username:password@hostname:9090/path#anchor",
			],
			[
				"https://hostname:9090/path?arg=value&arg2=value2#anchor",
				"https://hostname:9090/path?arg=value&arg2=value2#anchor",
			],
			[
				"https://username:password@hostname/path?arg=value&arg2=value2#anchor",
				"https://username:password@hostname/path?arg=value&arg2=value2#anchor",
			],
			[
				"https://username:password@hostname:9090/?arg=value&arg2=value2#anchor",
				"https://username:password@hostname:9090/?arg=value&arg2=value2#anchor",
			],
			[
				"https://username:password@hostname:9090?arg=value&arg2=value2#anchor",
				"https://username:password@hostname:9090?arg=value&arg2=value2#anchor",
			],
			[
				"hostname.com",
				"https://hostname.com",
			],
		];
	}
}
