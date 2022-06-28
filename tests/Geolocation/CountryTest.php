<?php

namespace Geolocation;

use Methodz\Helpers\Geolocation\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{

	public function testFindByIsoCode3()
	{
		self::assertNotNull(Country::findByIsoCode3("FRA"));
	}

	public function testFindById()
	{
		self::assertNotNull(Country::findById(73));
	}

	public function testFindByIsoCodeNumeric()
	{
		self::assertNotNull(Country::findByIsoCodeNumeric(250));
	}

	public function testFindAllByName()
	{
		self::assertNotEmpty(Country::findAllByName("fr%"));
	}

	public function testFindByIsoCode2()
	{
		self::assertNotNull(Country::findByIsoCode2("FR"));
	}

	public function testGetCountryLanguages()
	{
		$country = Country::findById(73);
		self::assertNotEmpty($country->getCountryLanguages());
	}
}