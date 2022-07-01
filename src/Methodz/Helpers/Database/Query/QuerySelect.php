<?php

namespace Methodz\Helpers\Database\Query;


use Methodz\Helpers\Database\DatabaseQueryResult;

class QuerySelect extends Query
{
	private array $select = [];
	private ?string $from = null;
	private array $joins = [];
	private ?string $where = null;
	private ?string $groupBy = null;
	private ?string $having = null;
	private ?string $orderBy = null;
	private ?int $limit = null;
	private ?int $offset = null;


	private function __construct() { }

	private function select(array $select): self
	{
		$this->select = $select;

		return $this;
	}

	public function from(string $from): self
	{
		$this->from = $from;

		return $this;
	}

	public function innerJoin(string $table, string $on): self
	{
		$this->joins[] = "INNER JOIN $table ON $on";

		return $this;
	}

	public function leftJoin(string $table, string $on): self
	{
		$this->joins[] = "LEFT JOIN $table ON $on";

		return $this;
	}

	public function rightJoin(string $table, string $on): self
	{
		$this->joins[] = "RIGHT JOIN $table ON $on";

		return $this;
	}

	public function where(string $where): self
	{
		$this->where = $where;

		return $this;
	}

	public function groupBy(string $groupBy): self
	{
		$this->groupBy = $groupBy;

		return $this;
	}

	public function having(string $having): self
	{
		$this->having = $having;

		return $this;
	}

	public function orderBY(string $orderBy): self
	{
		$this->orderBy = $orderBy;

		return $this;
	}

	public function limit(int $limit): self
	{
		$this->limit = $limit;

		return $this;
	}

	public function offset(int $offset): self
	{
		$this->offset = $offset;

		return $this;
	}

	protected function buildQuery(): static
	{
		$this->sql = "SELECT " . implode(', ', $this->select);
		if ($this->from !== null) {
			$this->addSQL("FROM $this->from");
		}
		if (count($this->joins) > 0) {
			$this->addSQL(implode(' ', $this->joins));
		}
		if ($this->where !== null) {
			$this->addSQL("WHERE $this->where");
		}
		if ($this->groupBy !== null) {
			$this->addSQL("GROUP BY $this->groupBy");
		}
		if ($this->having !== null) {
			$this->addSQL("HAVING $this->having");
		}
		if ($this->orderBy !== null) {
			$this->addSQL("ORDER BY $this->orderBy");
		}
		if ($this->limit !== null) {
			$this->addSQL("LIMIT $this->limit");
		}
		if ($this->offset !== null) {
			$this->addSQL("OFFSET $this->offset");
		}
		return $this;
	}

	public function execute(): DatabaseQueryResult
	{
		$this->buildQuery();
		return parent::execute();
	}

	public static function init(array $selects): self
	{
		return (new self())->select($selects);
	}
}
