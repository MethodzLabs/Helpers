<?php

namespace Database\Query;

use Models\Country;
use Models\SearchEngine;
use Models\SearchEngineTypeEnum;
use PHPUnit\Framework\TestCase;

class QuerySelectTest extends TestCase
{
	public function testSelectFrom()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE);

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE,
			$query->getSql()
		);
	}

	public function testSelectFromInnerJoin()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->innerJoin(Country::_TABLE, "`" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " INNER JOIN " . Country::_TABLE . " ON `" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`",
			$query->getSql()
		);
	}

	public function testSelectFromLeftJoin()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->leftJoin(Country::_TABLE, "`" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " LEFT JOIN " . Country::_TABLE . " ON `" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`",
			$query->getSql()
		);
	}

	public function testSelectFromRightJoin()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->rightJoin(Country::_TABLE, "`" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " RIGHT JOIN " . Country::_TABLE . " ON `" . SearchEngine::_TABLE . "`.`" . SearchEngine::_COUNTRY_ID . "`=`" . Country::_TABLE . "`.`" . Country::_ID . "`",
			$query->getSql()
		);
	}

	public function testSelectFromWhere()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->where("`" . SearchEngine::_TYPE . "`=:search_engine_type")
			->addParameter("search_engine_type", SearchEngineTypeEnum::GOOGLE_SEARCH);

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " WHERE `" . SearchEngine::_TYPE . "`=:search_engine_type",
			$query->getSql()
		);
		self::assertContains(SearchEngineTypeEnum::GOOGLE_SEARCH->toString(), $query->getParameters());
		self::assertContains("search_engine_type", array_keys($query->getParameters()));
	}

	public function testSelectFromGroupBy()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->groupBy("`" . SearchEngine::_TYPE . "`");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " GROUP BY `" . SearchEngine::_TYPE . "`",
			$query->getSql()
		);
	}

	public function testSelectFromHaving()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->having("`" . SearchEngine::_TYPE . "`='toto'");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " HAVING `" . SearchEngine::_TYPE . "`='toto'",
			$query->getSql()
		);
	}

	public function testSelectFromOrderBy()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->orderBy("`" . SearchEngine::_TYPE . "` ASC");

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " ORDER BY `" . SearchEngine::_TYPE . "` ASC",
			$query->getSql()
		);
	}

	public function testSelectFromLimit()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->limit(100);

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " LIMIT 100",
			$query->getSql()
		);
	}

	public function testSelectFromOffset()
	{
		$query = QueryHandler::select("*")
			->from(SearchEngine::_TABLE)
			->offset(50);

		self::assertEquals(
			"SELECT * FROM " . SearchEngine::_TABLE . " OFFSET 50",
			$query->getSql()
		);
	}
}
