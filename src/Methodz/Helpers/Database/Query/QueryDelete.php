<?php

namespace Methodz\Helpers\Database\Query;


use Methodz\Helpers\Database\DatabaseQueryResult;
use Methodz\Helpers\Tools\Tools;
use Methodz\Helpers\Tools\ToolsNormaliseStringTypeEnum;

class QueryDelete extends Query
{
	private ?string $table = null;
	private ?string $where = null;


	private function __construct() { }

	private function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	public function where(string $where): self
	{
		$this->where = $where;

		return $this;
	}

	protected function buildQuery(): static
	{
		$this->sql = "DELETE FROM $this->table";

		if ($this->where !== null) {
			$this->addSQL("WHERE $this->where");
		}
		return $this;
	}

	public function execute(): DatabaseQueryResult
	{
		$this->buildQuery();
		return parent::execute();
	}

	public static function init(string $table): self
	{
		return (new self())->table($table);
	}
}
