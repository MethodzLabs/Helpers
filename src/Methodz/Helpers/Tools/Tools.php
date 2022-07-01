<?php

namespace Methodz\Helpers\Tools;

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
}
