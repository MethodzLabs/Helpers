<?php

namespace Methodz\Helpers\Models\Builder;

class ModelsFieldLinkBuilder
{
	private string $target_type;
	private string $target_field;
	private string $name;
	private string $source_field;
	private bool $nullable;

	private function __construct(string $target_type, string $target_field, string $name, string $source_field, bool $nullable)
	{
		$this->target_type = $target_type;
		$this->target_field = $target_field;
		$this->name = $name;
		$this->source_field = $source_field;
		$this->nullable = $nullable;
	}

	/**
	 * @return string
	 */
	public function getTargetType(): string
	{
		return $this->target_type;
	}

	/**
	 * @return string
	 */
	public function getTargetField(): string
	{
		return $this->target_field;
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
	public function getSourceField(): string
	{
		return $this->source_field;
	}

	/**
	 * @return bool
	 */
	public function isNullable(): bool
	{
		return $this->nullable;
	}

	public static function init(string $target_type, string $target_field, string $name, string $source_field, bool $nullable): self
	{
		return new self($target_type, $target_field, $name, $source_field, $nullable);
	}

}
