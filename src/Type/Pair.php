<?php

namespace Methodz\Helpers\Type;

class Pair
{
	public mixed $first;
	public mixed $second;

	private function __construct(mixed $first, mixed $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	public static function init(mixed $first, mixed $second): self
	{
		return new self($first, $second);
	}
}
