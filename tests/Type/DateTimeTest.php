<?php

namespace Methodz\Helpers\Type;

use DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
	public function testIsAfter()
	{
		$before = _datetime();
		sleep(1);
		self::assertTrue(_datetime()->isAfter($before));
	}

	public function testCreateFromTimestamp()
	{
		$this->assertTrue(_datetime()->setTimestamp(1653820796)->equals(_datetime(1653820796)));
	}

	public function testFormatMax()
	{
		self::assertEquals((new DateTime())->format("Y-m-d H:i:s"), _datetime()->formatAsDateTime());
	}

	public function testIsValidDateTime()
	{
		self::assertTrue(_datetime()->isValid());
	}

	public function testSetDate()
	{
		$datetime = _datetime()->setDate(2022, 5, 29);
		self::assertEquals("2022-05-29", $datetime->format("Y-m-d"));
	}

	public function testEquals()
	{
		self::assertTrue(_datetime()->equals(_datetime()));
	}

	public function testCreateFromFormat()
	{
		$datetime = new DateTime();
		$datetimeFormatted = $datetime->format("YmdHis");
		self::assertEquals($datetime->getTimestamp(), _datetime($datetimeFormatted, "YmdHis")->getTimestamp());
	}

	public function testFormatFrenchMax()
	{
		self::assertEquals((new DateTime())->format("H:i:s d/m/Y"), _datetime()->formatAsDateTimeFrench());
	}

	public function test__construct()
	{
		$datetime = new DateTime();
		$myDatetime = _datetime();
		self::assertEquals($datetime->getTimestamp(), $myDatetime->getTimestamp());
	}

	public function testFormatMin()
	{
		self::assertEquals((new DateTime())->format("Y-m-d"), _datetime()->formatAsDate());
	}

	public function testSetTimestamp()
	{
		$datetime = _datetime("2022-05-29 12:32:56");
		$datetime->setTimestamp((new DateTime())->getTimestamp());
		self::assertEquals($datetime->getTimestamp(), _datetime()->getTimestamp());
	}

	public function testFormatFrenchMin()
	{
		self::assertEquals((new DateTime())->format("d/m/Y"), _datetime()->formatAsDateFrench());
	}

	public function test__toString()
	{
		$stringActual = (string)_datetime();
		$stringExpected = (new DateTime())->format("Y-m-d H:i:s");
		self::assertEquals($stringExpected, $stringActual);
	}

	public function testNow()
	{
		self::assertEquals((new DateTime())->getTimestamp(), _datetime()->getTimestamp());
	}

	public function testIsBefore()
	{
		$before = _datetime();
		sleep(1);
		self::assertTrue($before->isBefore(_datetime()));
	}
}
