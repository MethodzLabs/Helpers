<?php

namespace Methodz\Helpers\Accessors;


use Methodz\Helpers\Exceptions\IndexNotFoundException;

class Session
{
	private static string $key = "methodz_helpers_session";

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
		if (!isset($_SESSION)) session_start();
		if (!array_key_exists(self::$key, $_SESSION)) {
			$_SESSION[self::$key] = new self();
		}
		$_SESSION[self::$key][$key] = $value;
	}

	public static function getAll(): array
	{
		if (!isset($_SESSION)) session_start();
		if (!array_key_exists(self::$key, $_SESSION)) {
			$_SESSION[self::$key] = [];
		}
		return $_SESSION[self::$key];
	}

	public static function exist(int|string $key): bool
	{
		return array_key_exists($key, self::getAll());
	}
}
