<?php

namespace Models;

use Tools\Tools;

trait CommonTrait
{
	public function __toString(): string
	{
		return Tools::anyToString($this);
	}
}
