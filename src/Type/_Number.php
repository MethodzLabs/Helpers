<?php

namespace Methodz\Helpers\Type;

abstract class _Number extends _Any
{
	public function isPositive(): bool
	{
		return $this->getValue() > 0;
	}

	public function isNegative(): bool
	{
		return $this->getValue() < 0;
	}

	public function toInt(): _Int
	{
		return _int($this->getValue());
	}

	public function toFloat(): _Float
	{
		return _float($this->getValue());
	}
}
