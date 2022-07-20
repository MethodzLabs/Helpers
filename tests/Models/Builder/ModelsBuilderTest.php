<?php

namespace Models\Builder;

use Methodz\Helpers\Database\HelpersDatabase;
use Methodz\Helpers\Models\Builder\ModelsBuilder;
use PHPUnit\Framework\TestCase;


class ModelsBuilderTest extends TestCase
{
	public function testBuild()
	{
		ModelsBuilder::build(__DIR__ . "/../../../output/Models", HelpersDatabase::class);

		/*SearchEngine::fromArray([
			SearchEngine::_TYPE => "Google Search"
		]);*/

		self::assertTrue(true);
	}
}
