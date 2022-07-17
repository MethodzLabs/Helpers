<?php

namespace Database;

use Models\CommonEnumTrait;

enum DatabaseQueryResultStatus
{
	use CommonEnumTrait;

	case NO_DATA_FOUND;
	case OK;
	case PENDING;
	case ERROR;

	public function toString(): string
	{
		return $this->name;
	}
}
