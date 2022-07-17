<?php

namespace Database\Query;

use Database\DatabaseQueryResult;

/**
 * @method init
 */
interface QueryInterface
{
	public function execute(): DatabaseQueryResult;
}
