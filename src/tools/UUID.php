<?php

namespace Zaacom\helpers\tools;

use JetBrains\PhpStorm\Pure;

abstract class UUID
{
	private static array $history = [];

	public static function generate(int $length = 30): string
	{
		$res = "";
		$chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
		for ($i = 0; $i < $length; $i++) {
			$res .= $chars[array_rand($chars)];
		}
		self::$history[] = $res;
		return $res;
	}

	/**
	 * @return string[]
	 */
	public static function getHistory(): array
	{
		return self::$history;
	}

	#[Pure] public static function getLastUUID(): ?string
	{
		if (count(self::getHistory()) === 0) {
			return null;
		}
		return self::getHistory()[count(self::getHistory()) - 1];
	}
}
