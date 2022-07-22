<?php

namespace Methodz\Helpers\Type;

use Methodz\Helpers\Models\CommonTrait;

abstract class _Any
{
	use CommonTrait;

	public function getValue()
	{
		return $this->value;
	}



	public function __toString(): string
	{
		return $this->getValue();
	}
}
