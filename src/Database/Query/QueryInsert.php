<?php

namespace Database\Query;

use Database\DatabaseQueryResult;
use Exception;

class QueryInsert extends Query
{
	private ?string $table = null;
	private ?array $columns = null;
	private ?array $values = null;
	private ?QuerySelect $select = null;


	private function __construct() { }

	private function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	public function columns(array $columns): self
	{
		$this->columns = $columns;

		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function values(array $values): self
	{
		if (count($values) === 0) {
			throw new Exception("\$values cannot be empty");
		}

		if (!is_array($values[array_keys($values)[0]])) {
			$values = [$values];
		}

		$this->values = $values;

		return $this;
	}

	public function select(QuerySelect $select): self
	{
		$this->select = $select;

		return $this;
	}

	protected function buildQuery(): static
	{
		$this->sql = "INSERT INTO $this->table";

		if ($this->columns !== null) {
			$this->addSQL("(" . implode(', ', $this->columns) . ")");
		}

		if ($this->select !== null) {
			$this->addSQL($this->select->getSql());
			$this->addParameters($this->select->getParameters() ?? []);
		} else {
			$this->addSQL("VALUES");
			$v = [];
			foreach ($this->values as $row) {
				$i = count($v);
				$r = [];
				foreach ($row as $value) {
					$j = count($r);
					$r[] = ":" . $i . "_" . $j;

					$this->addParameter($i . "_" . $j, $value);
				}
				$v[] = "(" . implode(', ', $r) . ")";
			}
			$this->addSQL(implode(', ', $v));
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
