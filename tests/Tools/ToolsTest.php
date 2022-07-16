<?php

namespace Tools;

use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;
use Methodz\Helpers\Type\Pair;
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

	public function testParseString()
	{
		Tools::parseString("456464")->asNumber()->asInt();
		Tools::parseString("456.464")->asNumber()->asFloat();
		Tools::parseString("456,464")->asNumber()->asFloat();
		Tools::parseString("[12,23]")->asJsonArray();
		Tools::parseString("2021-10-10")->asDateTime();
		Tools::parseString("Un test")->asString();
		Tools::parseString("true")->asBoolean();

		self::assertTrue(true);
	}

	/**
	 * @dataProvider dataProviderParseNumber
	 */
	public function testParseNumber(string $number, Pair $expected, Pair $parameters)
	{
		$toolsNumber = Tools::parseNumber($number);
		self::assertEquals($expected->first, $toolsNumber->asFloat($parameters->first, $parameters->second));
		self::assertEquals($expected->second, $toolsNumber->asInt($parameters->first, $parameters->second));
	}

	public function dataProviderParseNumber(): array
	{
		return [
			["123", Pair::init(123.0, 123), Pair::init('.', ' ')],
			["123.456", Pair::init(123.456, 123), Pair::init('.', ' ')],
			["123 456.789", Pair::init(123456.789, 123456), Pair::init('.', ' ')],
			["123,456", Pair::init(123456.0, 123456), Pair::init('.', ',')],
			["123,456.789", Pair::init(123456.789, 123456), Pair::init('.', ',')],
		];
	}
}
