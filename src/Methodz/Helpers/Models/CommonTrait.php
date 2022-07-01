<?php

namespace Methodz\Helpers\Models;

trait CommonTrait
{
	public function toString(): string
	{
		return json_encode($this);
	}
}
