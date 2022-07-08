<?php

namespace Methodz\Helpers\Database;

use Methodz\Helpers\Models\CommonEnumTrait;

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
