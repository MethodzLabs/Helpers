<?php

namespace Database\Query;

use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QueryHandler;
use Methodz\Helpers\Database\Query\QuerySelect;
use Methodz\Helpers\Models\Country;
use Methodz\Helpers\Models\CountryLanguage;
use Methodz\Helpers\Models\SearchEngine;
use Methodz\Helpers\Models\SearchEngineTypeEnum;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;
use PHPUnit\Framework\TestCase;

class QueryInsertTest extends TestCase
{
	public function testInsert()
	{
		$query = QueryHandler::insert(SearchEngine::_TABLE)
			->values([1, 2, 3]);

		self::assertEquals(
			"INSERT INTO " . SearchEngine::_TABLE . " VALUES (:0_0, :0_1, :0_2)",
			$query->getQuery()
		);
	}

	public function testInsertColumns()
	{
		$query = QueryHandler::insert(SearchEngine::_TABLE)
			->columns([SearchEngine::_COUNTRY_ID, SearchEngine::_URL, SearchEngine::_TYPE])
			->values([1, 2, 3]);

		self::assertEquals(
			"INSERT INTO " . SearchEngine::_TABLE . " (" . SearchEngine::_COUNTRY_ID . ", " . SearchEngine::_URL . ", " . SearchEngine::_TYPE . ") VALUES (:0_0, :0_1, :0_2)",
			$query->getQuery()
		);
	}

	public function testInsertFrom()
	{
		$query = QueryHandler::insert(SearchEngine::_TABLE)
			->select(
				QueryHandler::select("*")
					->from(Country::_TABLE)
			);

		self::assertEquals(
			"INSERT INTO " . SearchEngine::_TABLE . " SELECT * FROM " . Country::_TABLE,
			$query->getQuery()
		);
	}
}
