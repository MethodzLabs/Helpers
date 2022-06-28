<?php

namespace Methodz\Helpers\Type;

enum Status
{
	case OK;
	case PENDING;
	case ERROR;

	public function isOk(): bool
	{
		return $this === self::OK;
	}

	public function isPending(): bool
	{
		return $this === self::PENDING;
	}

	public function isError(): bool
	{
		return $this === self::ERROR;
	}
}
