<?php

namespace Geolocation;

use Methodz\Helpers\Geolocation\City;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{

	public function testFindAllByName()
	{
		self::assertNotEmpty(City::findAllByName("Paris"));
	}

	public function testFindAllByCountryId()
	{
		self::assertNotEmpty(City::findAllByCountryId(73));
	}

	public function testFindById()
	{
		self::assertNotNull(City::findById(1));
	}
}
