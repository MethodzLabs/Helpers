<?php

namespace Methodz\Helpers\Tools\Part;

class ToolsNumber
{
	private string $str;

	private function __construct(string $str) { $this->str = $str; }

	public function asFloat(string $from_decimal_separator = ',', string $from_thousands_separator = ' '): float
	{
		$this->parseNumber($from_decimal_separator, $from_thousands_separator);
		return floatval($this->str);
	}

	public function asInt(string $from_decimal_separator = ',', string $from_thousands_separator = ' '): int
	{
		$this->parseNumber($from_decimal_separator, $from_thousands_separator);
		return intval($this->str);
	}

	private function parseNumber(string $from_decimal_separator, string $from_thousands_separator): void
	{
		$this->str = str_replace($from_decimal_separator, '.', $this->str);
		$this->str = str_replace($from_thousands_separator, '', $this->str);
	}

	public static function init(string $str): self
	{
		return new self($str);
	}

	public static function format(float $number, int $decimals = 2, string $decimal_separator = ',', string $thousands_separator = ' '): string
	{
		return number_format($number, $decimals, $decimal_separator, $thousands_separator);
	}
}
