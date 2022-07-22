<?php

namespace Methodz\Helpers\Models;

use PHPUnit\Framework\TestCase;
use function Methodz\Helpers\Type\_int;

class CountryTest extends TestCase
{

	public function testFindByIsoCode3()
	{
		self::assertNotNull(Country::findByIsoCode3("FRA"));
	}

	public function testFindById()
	{
		self::assertNotNull(Country::findById(_int(73)));
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
		$country = Country::findById(_int(73));
		self::assertNotEmpty($country->getCountryLanguageList());
	}

	public function testGetSearchEngines()
	{
		$country = Country::findById(_int(73));
		//print_r($country->getSearchEngines());
		self::assertNotEmpty($country->getSearchEngineList());
	}
}
