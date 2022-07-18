<?php

namespace Methodz\Helpers\Accessors;

use Methodz\Helpers\Exceptions\IndexNotFoundException;

class Get
{

	/**
	 * @throws IndexNotFoundException
	 */
	public static function get(int|string $key): mixed
	{
		if (!self::exist($key)) {
			throw new IndexNotFoundException($key);
		}
		return self::getAll()[$key];
	}

	public static function getOrSet(int|string $key, mixed $value): mixed
	{
		if (!self::exist($key)) {
			self::set($key, $value);
		}
		return self::getAll()[$key];
	}

	public static function set(int|string $key, mixed $value): void
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
