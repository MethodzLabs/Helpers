<?php

namespace Methodz\Helpers\Database;

use Methodz\Helpers\Models\CommonTrait;

enum DatabaseQueryResultStatus
{
	use CommonTrait;

	case NO_DATA_FOUND;
	case OK;
	case PENDING;
	case ERROR;

	public function toString(): string
	{
		return $this->name;
	}
}
