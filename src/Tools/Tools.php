<?php

namespace Methodz\Helpers\Tools;

use ReflectionClass;
use ReflectionException;
use Methodz\Helpers\Tools\Part\ToolsNumber;
use Methodz\Helpers\Tools\Part\ToolsString;

abstract class Tools
{
	private static array $history_uuid = [];

	public static function generateUUID(int $length = 30): string
	{
		$res = "";
		$chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
		for ($i = 0; $i < $length; $i++) {
			$res .= $chars[array_rand($chars)];
		}
		self::$history_uuid[] = $res;
		return $res;
	}

	/**
	 * @return string[]
	 */
	public static function getHistoryUUID(): array
	{
		return self::$history_uuid;
	}

	public static function getLastUUID(): ?string
	{
		if (count(self::getHistoryUUID()) === 0) {
			return null;
		}
		return self::getHistoryUUID()[count(self::getHistoryUUID()) - 1];
	}

	/**
	 * @param string                       $string
	 * @param ToolsNormaliseStringTypeEnum $type
	 *
	 * @return string
	 */
	public static function normaliseString(string $string, ToolsNormaliseStringTypeEnum $type = ToolsNormaliseStringTypeEnum::SNAKE_CASE): string
	{
		$string = str_replace(['_', '-'], ' ', $string);

		$words = explode(' ', $string);

		$string = "";

		foreach ($words as $word) {
			if (!empty($word)) {
				switch ($type) {
					case ToolsNormaliseStringTypeEnum::CAMEL_CASE:
					case ToolsNormaliseStringTypeEnum::PASCAL_CASE:
						$string .= ucfirst($word);
						break;
					case ToolsNormaliseStringTypeEnum::SNAKE_CASE:
						$string .= '_' . strtolower($word);
						break;
				}
			}
		}

		return match ($type) {
			ToolsNormaliseStringTypeEnum::PASCAL_CASE => lcfirst($string),
			ToolsNormaliseStringTypeEnum::SNAKE_CASE => trim($string, '_'),
			default => $string,
		};
	}


	/**
	 * @throws ReflectionException
	 */
	public static function anyToString(mixed $any): string
	{
		if (is_string($any)) {
			return '"' . str_replace('"', '\"', $any) . '"';
		} elseif (is_bool($any)) {
			return $any ? "true" : "false";
		} elseif (is_float($any) && is_nan($any)) {
			return "NaN";
		} elseif (is_null($any)) {
			return "NULL";
		} elseif (is_array($any)) {
			return self::arrayToString($any);
		} elseif (is_object($any)) {
			return self::objectToString($any);
		}

		return (string)$any;
	}

	/**
	 * @throws ReflectionException
	 */
	private static function objectToString(object $object): string
	{
		$str = "";
		$properties = [];

		$reflectionClass = new ReflectionClass($object::class);

		foreach ($reflectionClass->getProperties() as $property) {
			$properties[$property->name] = self::anyToString($property->getValue($object));
		}

		uksort($properties, function ($pA, $pB) {
			if ($pA === "id") {
				return -1;
			} elseif ($pB === "id") {
				return 1;
			}
			return $pA > $pB ? 1 : -1;
		});

		foreach ($properties as $name => $property) {
			if ($str === "") {
				$str = $object::class . "(";
			} else {
				$str .= ", ";
			}
			$str .= $name . ": " . $property;
		}


		return $str . ")";
	}

	private static function arrayToString(array $array): string
	{
		return "[" .
			implode(', ', array_map(function ($e) {
				return self::anyToString($e);
			}, $array)) . "]";
	}

	public static function parseString(string $string): ToolsString
	{
		return ToolsString::init($string);
	}

	public static function parseNumber(string|int|float $number): ToolsNumber
	{
		return ToolsNumber::init((string) $number);
	}
}
