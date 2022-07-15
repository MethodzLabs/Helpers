<?php

namespace Methodz\Helpers\Accessors;

use Methodz\Helpers\Date\DateTime;
use Methodz\Helpers\Exceptions\IndexNotFoundException;

class Post
{

	/**
	 * @throws IndexNotFoundException
	 */
	public static function get(int|string $key): array|DateTime|string|int|float|null
	{
		if (!array_key_exists($key, $_POST)) {
			throw new IndexNotFoundException($key);
		}
		return get_protected_data($key, $_POST);
	}

	public static function set(int|string $key, mixed $value)
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
