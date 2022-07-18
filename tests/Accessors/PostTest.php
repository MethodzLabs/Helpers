<?php

namespace Methodz\Helpers\Accessors;

use Methodz\Helpers\Exceptions\IndexNotFoundException;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{

	public function testGet()
	{
		$this->expectException(IndexNotFoundException::class);

		Post::get("notExist");
	}

	public function testGetAll()
	{
		self::assertEmpty(Post::getAll());
	}

	public function testExist()
	{
		self::assertFalse(Post::exist("notExist"));
	}

	public function testSet()
	{
		Post::set("key", "value");
		self::assertNotEmpty(Post::getAll());
	}
}
