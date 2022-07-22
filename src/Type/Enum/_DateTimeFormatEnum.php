<?php

namespace Methodz\Helpers\Type\Enum;

use Methodz\Helpers\Models\Structure\CommonEnumTrait;

enum _DateTimeFormatEnum: string
{
	use CommonEnumTrait;

	case DATETIME = "Y-m-d H:i:s";
	case DATE = "Y-m-d";
	case HOURS = "H:i:s";

	case DATETIME_FRENCH = "H:i:s d/m/Y";
	case DATE_FRENCH = "d/m/Y";

	public function toString(): string
	{
		return $this->value;
	}
}
