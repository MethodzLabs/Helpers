<?php

namespace Methodz\Helpers\Models\Builder;

class ModelsFieldBuilder
{
	private string $type;
	private string $name;
	private bool $nullable;
	private bool $enum;

	private function __construct(string $type, string $name, bool $nullable)
	{
		$this->type = $type;
		$this->name = $name;
		$this->nullable = $nullable;
		$this->enum = str_ends_with($type, "Enum");
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return bool
	 */
	public function isNullable(): bool
	{
		return $this->nullable;
	}

	/**
	 * @return bool
	 */
	public function isEnum(): bool
	{
		return $this->enum;
	}

	public static function init(string $type, string $name, bool $nullable): self
	{
		return new self($type, $name, $nullable);
	}

}
