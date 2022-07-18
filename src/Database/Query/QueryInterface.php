<?php

namespace Methodz\Helpers\Database\Query;

use Methodz\Helpers\Database\DatabaseQueryResult;

/**
 * @method init
 */
interface QueryInterface
{
	public function execute(): DatabaseQueryResult;
}
