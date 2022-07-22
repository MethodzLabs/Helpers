<?php

namespace Methodz\Helpers\Type;

use Methodz\Helpers\Type\Enum\_DateTimeFormatEnum;

function _string(string $value): _String
{
	return _String::init($value);
}

function _int(int $value): _Int
{
	return _Int::init($value);
}

function _float(float $value): _Float
{
	return _Float::init($value);
}

function _datetime(int|string|\DateTime $value = "now", string|_DateTimeFormatEnum $format = _DateTimeFormatEnum::DATETIME): _DateTime
{
	return _DateTime::init($value, $format);
}
