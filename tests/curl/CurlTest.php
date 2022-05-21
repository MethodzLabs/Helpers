<?php

namespace curl;

use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{

	public function testGet()
	{
		var_dump(parse_url("http://username:password@hostname:9090/path?arg=value&arg2=value2#anchor", PHP_URL_QUERY));
		var_dump(parse_url("https://www.zaacom.fr/definition-de-strategie-editoriale/?", PHP_URL_QUERY));
		var_dump(parse_url("zaacom.fr/definition-de-strategie-editoriale/", PHP_URL_QUERY));
		self::assertTrue(true);
	}
}
