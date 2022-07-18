<?php

namespace Methodz\Helpers\Accessors;

use Methodz\Helpers\Exceptions\IndexNotFoundException;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{

	public function testGet()
	{
		$this->expectException(IndexNotFoundException::class);

		Get::get("notExist");
	}

	public function testGetAll()
	{
		self::assertEmpty(Get::getAll());
	}

	public function testExist()
	{
		self::assertFalse(Get::exist("notExist"));
	}

	public function testSet()
	{
		Get::set("key", "value");
		self::assertNotEmpty(Get::getAll());
	}
}
