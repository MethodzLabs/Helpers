<?php

namespace Accessors;

use Methodz\Helpers\Accessors\Session;
use Methodz\Helpers\Exceptions\IndexNotFoundException;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

	public function testGet()
	{
		self::expectException(IndexNotFoundException::class);;


		Session::get("UndefinedKey");
	}

	public function testGetAll()
	{

	}

	public function testSet()
	{

	}

	public function testExist()
	{

	}
}
