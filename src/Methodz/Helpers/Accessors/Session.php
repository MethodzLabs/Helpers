<?php

namespace Methodz\Helpers\Accessors;


use Methodz\Helpers\Exceptions\IndexNotFoundException;
use Methodz\Helpers\Tools\Tools;

class Session
{
	private static string $key = "";

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

	public static function set(int|string $key, mixed $value)
	{
		if (!isset($_SESSION)) session_start();
		if (!array_key_exists(self::$key, $_SESSION)) {
			$_SESSION[self::$key] = new self();
		}
		$_SESSION[self::$key][$key] = $value;
	}

	public static function getAll(): array
	{
		print_r("GET");
		if (!isset($_SESSION)) session_start();
		if (self::$key === "") {
			self::$key = Tools::generateUUID();
		}

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
