<?php

namespace Methodz\Helpers\Type;

use Exception;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Type\Enum\_StringFormatEnum;

class _String extends _Any
{

	private string $value;

	public function __construct(string $value)
	{
		$this->value = $value;
	}

	public static function init(string $value): static
	{
		return new static($value);
	}

	/**
	 * Make a string's first character uppercase
	 *
	 * @return string
	 */
	public function ucfirst(): string
	{
		return ucfirst($this->value);
	}

	/**
	 * Make a string lowercase
	 *
	 * @return string
	 */
	public function lowercase(): string
	{
		return strtolower($this->value);
	}

	/**
	 * Make a string uppercase
	 *
	 * @return string
	 */
	public function uppercase(): string
	{
		return strtoupper($this->value);
	}

	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string
	 *
	 * @param string $characters - Optionally, the stripped characters can also be specified using the charlist parameter. Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
	 *
	 * @return string
	 */
	public function trim(string $characters = " \t\n\r\0\x0B"): string
	{
		return trim($this->value, $characters);
	}

	/**
	 * Strip whitespace (or other characters) from the beginning of a string
	 *
	 * @param string $characters - Optionally, the stripped characters can also be specified using the charlist parameter. Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
	 *
	 * @return string
	 */
	public function trimStart(string $characters = " \t\n\r\0\x0B"): string
	{
		return ltrim($this->value, $characters);
	}

	/**
	 * Strip whitespace (or other characters) from the end of a string.
	 *
	 * @param string $characters - Optionally, the stripped characters can also be specified using the charlist parameter. Simply list all characters that you want to be stripped. With .. you can specify a range of characters.
	 *
	 * @return string
	 */
	public function trimEnd(string $characters = " \t\n\r\0\x0B"): string
	{
		return rtrim($this->value, $characters);
	}

	/**
	 * The function returns true if the string starts from the $needle string or false otherwise.
	 *
	 * @param string $needle
	 *
	 * @return bool
	 */
	public function startsWith(string $needle): bool
	{
		return str_starts_with($this->value, $needle);
	}

	/**
	 * The function returns true if the string ends from the $needle string or false otherwise.
	 *
	 * @param string $needle
	 *
	 * @return bool
	 */
	public function endsWith(string $needle): bool
	{
		return str_ends_with($this->value, $needle);
	}

	/**
	 * Returns a string obtained by substituting the specified format
	 *
	 * @param _StringFormatEnum $format
	 *
	 * @return string
	 */
	public function format(_StringFormatEnum $format): string
	{
		return Tools::normaliseString($this->value, $format);
	}

	/**
	 * Get string length
	 *
	 * @return _Int
	 */
	public function length(): _Int
	{
		return _int(strlen($this->value));
	}

	/**
	 * @return string[]
	 */
	public function lines(): array
	{
		return explode("\n", $this->value);
	}

	/**
	 * @return string[]
	 * @throws Exception
	 */
	public function toCharArray(?_Int $startIndex = null, ?_Int $length = null): array
	{
		$array = str_split($this->value);
		$do = false;

		if ($startIndex !== null) {
			if ($startIndex->isNegative()) {
				throw new Exception("The start index cannot be less than 0.");
			}
			$do = true;
		} else {
			$startIndex = _int(0);
		}

		if ($length !== null) {
			if ($length->isNegative()) {
				throw new Exception("The length cannot be less than 0.");
			}
			$do = true;
		} else {
			$length = _int(count($array));
		}

		if ($do) {
			$arr = $array;
			$array = [];
			for ($i = $startIndex->getValue(); $i < min($startIndex->getValue() + $length->getValue(), count($arr)); $i++) {
				$array[] = $arr[$i];
			}
		}

		return $array;
	}

	/**
	 * Return true if this string is empty
	 *
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return empty($this->value);
	}

	/**
	 * Return true if this string is not empty
	 *
	 * @return bool
	 */
	public function isNotEmpty(): bool
	{
		return !$this->isEmpty();
	}

	/**
	 * Return true if this string is blank
	 *
	 * @return bool
	 */
	public function isBlank(): bool
	{
		return _string($this->trim($this->value))->isEmpty();
	}

	/**
	 * Return true if this string is not blank
	 *
	 * @return bool
	 */
	public function isNotBlank(): bool
	{
		return !$this->isBlank();
	}

	/**
	 * Return true if this string is empty or blank
	 *
	 * @return bool
	 */
	public function isEmptyOrBlank(): bool
	{
		return $this->isEmpty() || $this->isBlank();
	}
}
