<?php

namespace Tools;

use Models\CommonEnumTrait;

enum ToolsNormaliseStringTypeEnum
{
	use CommonEnumTrait;

	case CAMEL_CASE;
	case PASCAL_CASE;
	case SNAKE_CASE;
}
