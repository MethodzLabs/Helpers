<?php

namespace Methodz\Framework\Accessor;


use Methodz\Helpers\Date\DateTime;
use Zaacom\exception\IndexNotFoundException;

class ZGet
{

	private function __construct() { }

	public static function get(int|string $key): array|DateTime|string|int|float|null
	{
		if (!array_key_exists($key, $_GET)) {
			throw new IndexNotFoundException($key);
		}
		return get_protected_data($key, $_GET);
	}

	public static function getOrCreate(int|string $key, mixed $elseValue = null): array|DateTime|string|int|float|null
	{
		if (!array_key_exists($key, $_GET)) {
			self::set($key, $elseValue);
		}
		return self::get($key);
	}

	public static function set(int|string $key, mixed $value)
	{
		$_GET[$key] = $value;
	}

	public static function getAll(): array
	{
		return $_GET;
	}

	public static function exist(int|string $key): bool
	{
		return array_key_exists($key, $_GET);
	}
}
