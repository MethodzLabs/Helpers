<?php

namespace Models;

use Tools\Tools;

trait CommonEnumTrait
{
	public function toString(): string
	{
		return Tools::anyToString($this);
	}
}
