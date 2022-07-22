<?php

namespace Methodz\Helpers\Database\Query;

use Methodz\Helpers\Models\Enum\SearchEngineTypeEnum;
use Methodz\Helpers\Models\SearchEngine;
use PHPUnit\Framework\TestCase;

class QueryUpdateTest extends TestCase
{
	public function testUpdate()
	{
		$query = QueryHandler::update(SearchEngine::_TABLE)
			->set([
				SearchEngine::_COUNTRY_ID => 3,
				SearchEngine::_TYPE => SearchEngineTypeEnum::BING_SEARCH,
			]);

		self::assertEquals(
			"UPDATE " . SearchEngine::_TABLE . " SET  " . SearchEngine::_COUNTRY_ID . " = :country_id , " . SearchEngine::_TYPE . " = :type",
			$query->getSql()
		);
		self::assertContains(SearchEngineTypeEnum::BING_SEARCH->toString(), $query->getParameters());
		self::assertContains("type", array_keys($query->getParameters()));
	}

	public function testUpdateWhere()
	{
		$query = QueryHandler::update(SearchEngine::_TABLE)
			->set([
				SearchEngine::_COUNTRY_ID => 3,
				SearchEngine::_TYPE => SearchEngineTypeEnum::BING_SEARCH,
			])
			->where("`" . SearchEngine::_URL . "` LIKE :url")
			->addParameter('url', "Google.fr");

		self::assertEquals(
			"UPDATE " . SearchEngine::_TABLE . " SET  " . SearchEngine::_COUNTRY_ID . " = :country_id , " . SearchEngine::_TYPE . " = :type WHERE `" . SearchEngine::_URL . "` LIKE :url",
			$query->getSql()
		);
		self::assertContains(SearchEngineTypeEnum::BING_SEARCH->toString(), $query->getParameters());
		self::assertContains("type", array_keys($query->getParameters()));
		self::assertContains("Google.fr", $query->getParameters());
		self::assertContains("url", array_keys($query->getParameters()));
	}
}
