<?php

namespace Database;

use Methodz\Helpers\Database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

	public function testGetData()
	{
		$result = Database::getData("SELECT * FROM `city`");
		self::assertTrue($result->isOK());
	}

	public function testGetColumn()
	{
		$result = Database::getColumn("name", "SELECT * FROM `city`");
		self::assertTrue($result->isOK());
	}

	public function testGetValues()
	{
		$result = Database::getValues("SELECT * FROM `city`");
		self::assertTrue($result->isOK());
	}

	public function testGetValue()
	{
		$result = Database::getValue("SELECT * FROM `city`");
		self::assertTrue($result->isOK());
	}

	public function testGetRow()
	{
		$result = Database::getRow("SELECT * FROM `city`");
		self::assertTrue($result->isOK());
	}
}
