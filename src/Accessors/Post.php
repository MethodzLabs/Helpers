<?php

namespace Accessors;

use Exceptions\IndexNotFoundException;

class Post
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

	public static function set(int|string $key, mixed $value): void
	{
		$_POST[$key] = $value;
	}

	public static function getAll(): array
	{
		return $_POST;
	}

	public static function exist(int|string $key): bool
	{
		return array_key_exists($key, $_POST);
	}
}
