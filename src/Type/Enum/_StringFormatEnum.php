<?php

namespace Methodz\Helpers\Type\Enum;

use Methodz\Helpers\Models\Structure\CommonEnumTrait;

enum _StringFormatEnum
{
	use CommonEnumTrait;

	case CAMEL_CASE;
	case PASCAL_CASE;
	case SNAKE_CASE;
}
