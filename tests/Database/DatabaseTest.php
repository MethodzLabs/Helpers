<?php

namespace Database;

use Methodz\Helpers\Database\Database;
use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QueryHandler;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

	public function testGetData()
	{
		$result = Database::getData(QueryHandler::select("*")->from("`city`"));
		//print_r($result);
		self::assertTrue($result->isOK());
	}

	public function testGetColumn()
	{
		$result = Database::getColumn("name", QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValues()
	{
		$result = Database::getValues(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValue()
	{
		$result = Database::getValue(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetRow()
	{
		$result = Database::getRow(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}


}
