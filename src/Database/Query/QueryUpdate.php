<?php

namespace Methodz\Helpers\Database\Query;


use Methodz\Helpers\Database\DatabaseQueryResult;
use Methodz\Helpers\Tools\Tools;

class QueryUpdate extends Query
{
	private ?string $table = null;
	private array $set = [];
	private ?string $where = null;


	private function __construct() { }

	private function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	public function set(array $set): self
	{
		$this->set = $set;

		return $this;
	}

	public function where(string $where): self
	{
		$this->where = $where;

		return $this;
	}

	protected function buildQuery(): static
	{
		$this->sql = "UPDATE $this->table SET ";

		$i = 0;
		foreach ($this->set as $k => $value) {
			$key = Tools::normaliseString($k);
			$this->addParameter($key, $value);
			if ($i !== 0) {
				$this->addSQL(",");
			}
			$this->addSQL("$k = :$key");

			$i++;
		}

		if ($this->where !== null) {
			$this->addSQL("WHERE $this->where");
		}
		return $this;
	}

	public static function init(string $table): self
	{
		return (new self())->table($table);
	}
}
