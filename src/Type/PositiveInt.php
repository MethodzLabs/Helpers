<?php

namespace Type;

use Exception;

class PositiveInt
{
	private int $value;

	private function __construct(int $value)
	{
		$this->value = $value;
	}

	public function getValue(): int
	{
		return $this->value;
	}

	/**
	 * @throws Exception
	 */
	public static function init(mixed $value): self
	{
		if ($value < 0) {
			throw new Exception("The number cannot be less than 0.");
		}
		return new self($value);
	}
}
