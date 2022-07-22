<?php

namespace Methodz\Helpers\Models\Structure;

use Methodz\Helpers\Tools\Tools;

trait CommonTrait
{
	public function __toString(): string
	{
		return Tools::anyToString($this);
	}
}
