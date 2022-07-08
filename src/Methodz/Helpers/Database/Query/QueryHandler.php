<?php

namespace Methodz\Helpers\Database\Query;

abstract class QueryHandler
{
	public static function select(string ...$selects): QuerySelect
	{
		return QuerySelect::init($selects);
	}

	public static function update(string $table): QueryUpdate
	{
		return QueryUpdate::init($table);
	}

	public static function delete(string $table): QueryDelete
	{
		return QueryDelete::init($table);
	}

	public static function insert(string $table): QueryInsert
	{
		return QueryInsert::init($table);
	}
}
