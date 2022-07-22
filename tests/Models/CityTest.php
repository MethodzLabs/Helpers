<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Database\Query\QueryHandler;
use PHPUnit\Framework\TestCase;
use function Methodz\Helpers\Type\_int;

class CityTest extends TestCase
{

	public function testFindAllByName()
	{
		self::assertNotEmpty(City::findAllByQuery(QueryHandler::select("*")->from(City::_TABLE)->where(City::_NAME . " LIKE 'Paris'")));
	}

	public function testFindAllByCountryId()
	{
		self::assertNotEmpty(City::findAllByQuery(QueryHandler::select("*")->from(City::_TABLE)->where(City::_COUNTRY_ID." = 73")));
	}

	public function testFindById()
	{
		self::assertNotNull(City::findById(_int(1)));
	}
}
