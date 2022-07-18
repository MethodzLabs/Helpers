<?php

namespace Methodz\Helpers\Date;

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

	public function testIsAfter()
	{
		$before = DateTime::now();
		sleep(1);
		self::assertTrue(DateTime::now()->isAfter($before));
	}

	public function testCreateFromTimestamp()
	{
		$this->assertTrue(DateTime::now()->setTimestamp(1653820796)->equals(DateTime::createFromTimestamp(1653820796)));
	}

	public function testFormatMax()
	{
		self::assertEquals((new \DateTime())->format("Y-m-d H:i:s"), DateTime::now()->formatMax());
	}

	public function testIsValidDateTime()
	{
		self::assertTrue(DateTime::now()->isValidDateTime());
	}

	public function testSetDate()
	{
		$datetime = DateTime::now()->setDate(2022, 5, 29);
		self::assertEquals("2022-05-29", $datetime->format("Y-m-d"));
	}

	public function testEquals()
	{
		self::assertTrue(DateTime::now()->equals(DateTime::now()));
	}

	public function testCreateFromFormat()
	{
		$datetime = new \DateTime();
		$datetimeFormatted = $datetime->format("YmdHis");
		self::assertEquals($datetime->getTimestamp(), DateTime::createFromFormat("YmdHis", $datetimeFormatted)->getTimestamp());
	}

	public function testFormatFrenchMax()
	{
		self::assertEquals((new \DateTime())->format("H:i:s d/m/Y"), DateTime::now()->formatFrenchMax());
	}

	public function test__construct()
	{
		$datetime = new \DateTime();
		$myDatetime = new DateTime();
		self::assertEquals($datetime->getTimestamp(), $myDatetime->getTimestamp());
	}

	public function testFormatMin()
	{
		self::assertEquals((new \DateTime())->format("Y-m-d"), DateTime::now()->formatMin());
	}

	public function testSetTimestamp()
	{
		$datetime = new DateTime("2022-05-29 12:32:56");
		$datetime->setTimestamp((new \DateTime())->getTimestamp());
		self::assertEquals($datetime->getTimestamp(), DateTime::now()->getTimestamp());
	}

	public function testFormatFrenchMin()
	{
		self::assertEquals((new \DateTime())->format("d/m/Y"), DateTime::now()->formatFrenchMin());
	}

	public function test__toString()
	{
		$stringActual = (string)DateTime::now();
		$stringExpected = (new \DateTime())->format("Y-m-d H:i:s");
		self::assertEquals($stringExpected, $stringActual);
	}

	public function testNow()
	{
		self::assertEquals((new \DateTime())->getTimestamp(), DateTime::now()->getTimestamp());
	}

	public function testIsBefore()
	{
		$before = DateTime::now();
		sleep(1);
		self::assertTrue($before->isBefore(DateTime::now()));
	}
}
