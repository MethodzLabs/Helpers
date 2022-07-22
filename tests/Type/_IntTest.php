<?php

namespace Type;

use Methodz\Helpers\Type\_Any;
use Methodz\Helpers\Type\_Int;
use PHPUnit\Framework\TestCase;
use function Methodz\Helpers\Type\_int;

class _IntTest extends TestCase
{
	public function testToString() {

		var_dump(_int(123) instanceof _Any);

		self::assertEquals("123", _int(123));
	}
}
