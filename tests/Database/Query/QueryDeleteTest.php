<?php

namespace Methodz\Helpers\Database\Query;

use Methodz\Helpers\Models\SearchEngine;
use PHPUnit\Framework\TestCase;

class QueryDeleteTest extends TestCase
{
	public function testDelete()
	{
		$query = QueryHandler::delete(SearchEngine::_TABLE);

		self::assertEquals(
			"DELETE FROM " . SearchEngine::_TABLE,
			$query->getSql()
		);
	}

	public function testDeleteWhere()
	{
		$query = QueryHandler::delete(SearchEngine::_TABLE)
			->where("`" . SearchEngine::_URL . "` LIKE :url")
			->addParameter('url', "Google.fr");

		self::assertEquals(
			"DELETE FROM " . SearchEngine::_TABLE . " WHERE `" . SearchEngine::_URL . "` LIKE :url",
			$query->getSql()
		);
		self::assertContains("Google.fr", $query->getParameters());
		self::assertContains("url", array_keys($query->getParameters()));
	}
}
