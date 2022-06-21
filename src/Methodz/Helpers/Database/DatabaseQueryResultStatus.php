<?php

namespace Methodz\Helpers\Database;

enum DatabaseQueryResultStatus
{
	case NO_DATA_FOUND;
	case OK;
	case PENDING;
	case ERROR;
}
