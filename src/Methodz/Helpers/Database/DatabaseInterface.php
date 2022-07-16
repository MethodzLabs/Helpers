<?php

namespace Methodz\Helpers\Database;

use Exception;
use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QuerySelect;
use PDO;
use Throwable;

/**
 * @method getDatabase
 */
interface DatabaseInterface
{
	public static function executeRequest(Query $query): DatabaseQueryResult;
	public static function getColumn(int|string $index, QuerySelect $query): DatabaseQueryResult;
	public static function getValue(QuerySelect $query): DatabaseQueryResult;
	public static function getRow(QuerySelect $query): DatabaseQueryResult;
	public static function getValues(QuerySelect $query): DatabaseQueryResult;
	public static function getData(QuerySelect $query, ?string $keyAsIndex = null): DatabaseQueryResult;
	public static function getLastInsertId(): int;

}
