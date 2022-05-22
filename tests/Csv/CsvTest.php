<?php

namespace Csv;

use Methodz\Helpers\Csv\Csv;
use Methodz\Helpers\File\File;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{

	public function testFromFile()
	{
		//Csv::fromFile(__DIR__ . "/../..", "test.csv");

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
		Csv::fromArray([
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
		self::assertTrue(true);
	}
}
