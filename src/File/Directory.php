<?php

namespace Methodz\Helpers\File;

use Exception;

abstract class Directory
{
	/**
	 * Create directory if not exist
	 *
	 * @param string $path
	 * @param int    $permission
	 *
	 * @return bool
	 */
	public static function create(string $path, int $permission = 0777): bool
	{
		if (self::exist($path)) {
			return true;
		}
		return mkdir($path, $permission, true);
	}

	/**
	 * Delete directory
	 *
	 * @param string $path
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function delete(string $path): bool
	{
		if (!self::exist($path)) {
			throw new Exception($path . " is not a directory");
		}

		foreach (scandir($path) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}

			if (!self::delete($path . DIRECTORY_SEPARATOR . $item)) {
				return false;
			}

		}

		return rmdir($path);
	}

	/**
	 * Check if directory exist
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function exist(string $path): bool
	{
		return is_dir($path);
	}
}
