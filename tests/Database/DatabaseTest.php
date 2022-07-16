<?php

namespace Database;

use Methodz\Helpers\Database\DatabaseHelpers;
use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QueryHandler;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

	public function testGetData()
	{
		$result = DatabaseHelpers::getData(QueryHandler::select("*")->from("`city`"));
		//print_r($result);
		self::assertTrue($result->isOK());
	}

	public function testGetColumn()
	{
		$result = DatabaseHelpers::getColumn("name", QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValues()
	{
		$result = DatabaseHelpers::getValues(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValue()
	{
		$result = DatabaseHelpers::getValue(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetRow()
	{
		$result = DatabaseHelpers::getRow(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}


}
