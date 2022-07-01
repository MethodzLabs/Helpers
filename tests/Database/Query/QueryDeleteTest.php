<?php

namespace Database\Query;

use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QueryHandler;
use Methodz\Helpers\Models\Country;
use Methodz\Helpers\Models\CountryLanguage;
use Methodz\Helpers\Models\SearchEngine;
use Methodz\Helpers\Models\SearchEngineTypeEnum;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;
use PHPUnit\Framework\TestCase;

class QueryDeleteTest extends TestCase
{
	public function testDelete()
	{
		$query = QueryHandler::delete(SearchEngine::_TABLE);

		self::assertEquals(
			"DELETE FROM " . SearchEngine::_TABLE,
			$query->getQuery()
		);
	}

	public function testDeleteWhere()
	{
		$query = QueryHandler::delete(SearchEngine::_TABLE)
			->where("`" . SearchEngine::_URL . "` LIKE :url")
			->addParameter('url', "Google.fr");

		self::assertEquals(
			"DELETE FROM " . SearchEngine::_TABLE . " WHERE `" . SearchEngine::_URL . "` LIKE :url",
			$query->getQuery()
		);
		self::assertContains("Google.fr", $query->getParameters());
		self::assertContains("url", array_keys($query->getParameters()));
	}
}
