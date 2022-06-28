<?php

namespace Encrypt;

use Methodz\Helpers\Encrypt\Encrypt;
use Methodz\Helpers\Geolocation\Country;
use Methodz\Helpers\Geolocation\Language;
use PHPUnit\Framework\TestCase;

class EncryptTest extends TestCase
{

	public function testEncode()
	{
		var_dump(Encrypt::encode("coucou"));

		self::assertTrue(true);
	}

	public function testDecode()
	{


		self::assertTrue(true);
	}

	public function testTry()
	{
		print_r(Language::findAll());

		self::assertTrue(true);
	}
}
