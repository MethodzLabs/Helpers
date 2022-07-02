<?php

namespace Tools;

use Methodz\Helpers\Models\City;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;
use PHPUnit\Framework\TestCase;

class ToolsTest extends TestCase
{

	/**
	 * @param string                       $expected
	 * @param ToolsNormaliseStringTypeEnum $type
	 *
	 * @dataProvider dataProviderNormaliseString
	 */
	public function testNormaliseString(string $expected, ToolsNormaliseStringTypeEnum $type)
	{
		self::assertEquals($expected, Tools::normaliseString("PhraSe De_ -Te-st", $type));
	}

	public function dataProviderNormaliseString(): array
	{
		return [
			["phrase_de_te_st", ToolsNormaliseStringTypeEnum::SNAKE_CASE],
			["phraSeDeTeSt", ToolsNormaliseStringTypeEnum::PASCAL_CASE],
			["PhraSeDeTeSt", ToolsNormaliseStringTypeEnum::CAMEL_CASE],
		];
	}
}
