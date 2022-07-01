<?php

namespace Methodz\Helpers\Tools;

use Methodz\Helpers\Models\CommonTrait;

enum ToolsNormaliseStringTypeEnum
{
	use CommonTrait;

	case CAMEL_CASE;
	case PASCAL_CASE;
	case SNAKE_CASE;

	public function toString(): string
	{
		return $this->name;
	}
}
