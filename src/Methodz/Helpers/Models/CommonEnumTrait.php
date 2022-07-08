<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Tools\Tools;

trait CommonEnumTrait
{
	public function toString(): string
	{
		return Tools::anyToString($this);
	}
}
