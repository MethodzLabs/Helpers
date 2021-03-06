<?php

namespace Methodz\Helpers\Database\Query;

use Methodz\Helpers\Database\HelpersDatabase;
use Methodz\Helpers\Database\DatabaseQueryResult;
use Methodz\Helpers\Type\_Any;

abstract class Query implements QueryInterface
{
	protected string $sql = "";
	protected ?array $parameters = null;

	protected function addSQL(string $sqlPart): static
	{
		$this->sql .= " $sqlPart";

		return $this;
	}

	public function getSql(): string
	{
		$this->buildQuery();
		return $this->sql;
	}

	public function getParameters(): ?array
	{
		return $this->parameters;
	}

	public function addParameter(string $key, mixed $value): static
	{
		if ($this->parameters === null) {
			$this->parameters = [];
		}

		$this->parameters[$key] = $value;
		if (is_object($value)) {
			if ($value instanceof _Any) {
				$this->parameters[$key] = $value->__toString();
			} else {
				$this->parameters[$key] = $value->toString();}
		}
		return $this;
	}

	public function addParameters(array $parameters): static
	{
		if ($this->parameters === null) {
			$this->parameters = [];
		}

		return $this->setParameters(array_merge($this->parameters, $parameters));
	}

	public function setParameters(?array $parameters): static
	{
		$this->parameters = $parameters;

		if ($parameters !== null) {
			$this->parameters = [];
			foreach ($parameters as $key => $parameter) {
				$this->addParameter($key, $parameter);
			}
		}

		return $this;
	}

	protected function buildQuery(): static
	{
		return $this;
	}
}
