<?php

namespace Methodz\Helpers\Tools;

use Methodz\Helpers\Models\CommonEnumTrait;

enum ToolsNormaliseStringTypeEnum
{
	use CommonEnumTrait;

	case CAMEL_CASE;
	case PASCAL_CASE;
	case SNAKE_CASE;
}
