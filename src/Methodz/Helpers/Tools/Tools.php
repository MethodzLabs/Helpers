<?php

namespace Methodz\Helpers\Tools;

use ReflectionClass;
use ReflectionException;

abstract class Tools
{
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
}
