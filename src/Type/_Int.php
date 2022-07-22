<?php

namespace Methodz\Helpers\Type;

use Methodz\Helpers\Models\CommonTrait;

class _Int extends _Number
{

	protected int $value;

	public function __construct(int $value)
	{
		$this->value = $value;
	}

	public static function init(int $value): self
	{
		return new self($value);
	}
}
