<?php

namespace Models\Builder;

use Methodz\Helpers\Database\HelpersDatabase;
use Methodz\Helpers\Models\Builder\ModelsBuilder;
use PHPUnit\Framework\TestCase;


class ModelsBuilderTest extends TestCase
{
	public function testBuild()
	{
		ModelsBuilder::build(__DIR__ . "/../../../src/Models", HelpersDatabase::class);

		self::assertTrue(true);
	}
}
