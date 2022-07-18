<?php

namespace Methodz\Helpers\Database;

use Methodz\Helpers\Database\Query\QueryHandler;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

	public function testGetData()
	{
		$result = HelpersDatabase::getData(QueryHandler::select("*")->from("`city`"));
		//print_r($result);
		self::assertTrue($result->isOK());
	}

	public function testGetColumn()
	{
		$result = HelpersDatabase::getColumn("name", QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValues()
	{
		$result = HelpersDatabase::getValues(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetValue()
	{
		$result = HelpersDatabase::getValue(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}

	public function testGetRow()
	{
		$result = HelpersDatabase::getRow(QueryHandler::select("*")->from("`city`"));
		self::assertTrue($result->isOK());
	}


}
