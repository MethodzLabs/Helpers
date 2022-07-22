<?php

namespace Methodz\Helpers\Database;

use Methodz\Helpers\Type\_DateTime;
use PDOStatement;
use Throwable;
use function Methodz\Helpers\Type\_datetime;

class DatabaseQueryResult
{
	private DatabaseQueryResultStatus $status;
	private mixed $result;
	private _DateTime $datetime_start;
	private ?_DateTime $datetime_end;
	private string $query;
	private ?array $parameters;
	private ?Throwable $error;
	private ?PDOStatement $PDOStatement;

	private function __construct(string $query, ?array $parameters)
	{
		$this->status = DatabaseQueryResultStatus::PENDING;
		$this->result = null;
		$this->datetime_start = _datetime();
		$this->datetime_end = null;
		$this->query = $query;
		$this->parameters = $parameters;
	}

	public function getStatus(): DatabaseQueryResultStatus
	{
		return $this->status;
	}

	public function setStatus(DatabaseQueryResultStatus $status): self
	{
		$this->status = $status;

		return $this;
	}

	public function getResult(): mixed
	{
		return $this->result;
	}

	public function setResult(mixed $result): self
	{
		if ($this->datetime_end === null) {
			$this->datetime_end = _datetime();
		}
		$this->result = $result;

		return $this;
	}

	public function isOK(): bool
	{
		return $this->getStatus() === DatabaseQueryResultStatus::OK;
	}

	public function getDatetimeStart(): _DateTime
	{
		return $this->datetime_start;
	}

	public function getDatetimeEnd(): ?_DateTime
	{
		return $this->datetime_end;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

	public function getParameters(): ?array
	{
		return $this->parameters;
	}

	public function getError(): ?Throwable
	{
		return $this->error;
	}

	public function setError(Throwable $error): self
	{
		$this->setStatus(DatabaseQueryResultStatus::ERROR);
		$this->error = $error;

		return $this;
	}

	public function getPDOStatement(): ?PDOStatement
	{
		return $this->PDOStatement;
	}

	public function setPDOStatement(PDOStatement $PDOStatement): self
	{
		$this->PDOStatement = $PDOStatement;

		return $this;
	}

	/**
	 * @param string     $query
	 * @param array|null $parameters
	 *
	 * @return DatabaseQueryResult
	 */
	public static function init(string $query, ?array $parameters): self
	{
		return new self($query, $parameters);
	}


}
