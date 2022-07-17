<?php

namespace Database;

use Database\Query\Query;
use Database\Query\QuerySelect;

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
