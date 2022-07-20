<?php

namespace Methodz\Helpers\Models\Builder;

class ModelsFieldEnumBuilder
{
	private string $namespace;
	private string $name;
	/**
	 * @var string[]
	 */
	private array $values;

	private function __construct(string $name, string $namespace, array $values)
	{
		$this->name = $name;
		$this->namespace = $namespace;
		$this->values = $values;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}

	public static function init(string $name, string $namespace, array $values): self
	{
		return new self($name, $namespace, $values);
	}
}
