<?php

namespace Database;

use Methodz\Helpers\Database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

	public function testGetData()
	{
		$result = Database::getData("SELECT * FROM `country`");
		print_r($result);
		self::assertTrue(true);
	}

	public function testGetColumn()
	{

	}

	public function testGetValues()
	{

	}

	public function testGetValue()
	{

	}

	public function testGetLastInsertId()
	{

	}

	public function testUpdate()
	{

	}

	public function testInsert()
	{

	}

	public function testGetRow()
	{

	}

	public function testDelete()
	{

	}
}
