<?php

namespace Methodz\Helpers\Type;

use Methodz\Helpers\Models\CommonTrait;

class _Float extends _Number
{

	protected float $value;

	public function __construct(float $value)
	{
		$this->value = $value;
	}

	public static function init(float $value): self
	{
		return new self($value);
	}
}
