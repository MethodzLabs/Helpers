<?php

namespace Zaacom\helpers\files;

abstract class File
{
	/**
	 * @param string $path without / at end
	 * @param string $fileName
	 * @param string $content
	 *
	 * @return false|int
	 */
	public static function put(string $path, string $fileName, string $content): bool|int
	{
		if (str_ends_with($path, DIRECTORY_SEPARATOR)) {
			$path = rtrim($path);
		}
		if (!is_dir($path)) {
			Directory::create($path);
		}
		return file_put_contents($path . DIRECTORY_SEPARATOR . trim($fileName, DIRECTORY_SEPARATOR), $content);
	}

	/**
	 * @param string $path
	 * @param string $fileName can be empty
	 *
	 * @return bool|string
	 */
	public static function get(string $path, string $fileName = ""): bool|string
	{
		if (!empty($fileName)) {
			$fileName = DIRECTORY_SEPARATOR . $fileName;
		}
		return file_get_contents($path . $fileName);
	}

	public static function delete(string $path, string $fileName = ""): bool
	{
		if (!empty($fileName)) {
			$fileName = DIRECTORY_SEPARATOR . $fileName;
		}
		if (file_exists($path . $fileName)) {
			return unlink($path . $fileName);
		}
		return false;
	}
}
