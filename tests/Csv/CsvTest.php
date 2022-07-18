<?php

namespace Methodz\Helpers\Csv;

use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{

	public function testFromFile()
	{
		$csv = Csv::fromFile(__DIR__ . "/../../output", "testFromArray.csv");
		print_r($csv->getData());
		self::assertTrue(true);
	}

	public function testFromString()
	{
		$string = "target;visits;users\ngolang.org;\"4491179\";1400453\nblog.golang.org;402104;20489\ntour.golang.org/welcome/;10131;11628";
		Csv::fromString($string);
		self::assertTrue(true);
	}

	public function testFromArray()
	{
		$csv = Csv::fromArray([
			[
				"a" => 1,
				"b" => 2,
				"c" => 3,
			],
			[
				"a" => 1,
				"b" => 2,
				"z" => -1,
			],
		]);
		print_r($csv->getData());
		$csv->save(__DIR__ . "/../../output", "testFromArray.csv");
		self::assertTrue(true);
	}
}
