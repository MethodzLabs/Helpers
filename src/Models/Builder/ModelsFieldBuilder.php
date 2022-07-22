<?php

namespace Methodz\Helpers\Models\Builder;

class ModelsFieldBuilder
{
	private string $type;
	private string $function_type;
	private string $name;
	private bool $nullable;
	private bool $enum;
	private string $default_value;
	private bool $have_default_value;

	private function __construct(string $name, bool $nullable)
	{
		$this->type = "";
		$this->name = $name;
		$this->nullable = $nullable;
		$this->enum = false;
		$this->have_default_value = false;
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
	 * @return string
	 */
	public function getFunctionType(): string
	{
		return $this->function_type;
	}

	/**
	 * @param string $function_type
	 *
	 * @return ModelsFieldBuilder
	 */
	public function setFunctionType(string $function_type): static
	{
		$this->function_type = $function_type;

		return $this;
	}

	/**
	 * @param array|string $type
	 *
	 * @return ModelsFieldBuilder
	 */
	public function setType(array|string $type): static
	{
		if (is_array($type)) {
			$this->setFunctionType($type['function']);
			$type = $type['class'];
		}
		$this->type = $type;
		$this->enum = str_ends_with($this->type, "Enum");

		return $this;
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

	/**
	 * @return string
	 */
	public function getDefaultValue(): string
	{
		return $this->default_value;
	}

	/**
	 * @param string|null $default_value
	 *
	 * @return ModelsFieldBuilder
	 */
	public function setDefaultValue(?string $default_value): static
	{
		$this->have_default_value = true;
		$this->default_value = $default_value ?? "null";

		return $this;
	}

	/**
	 * @return bool
	 */
	public function haveDefaultValue(): bool
	{
		return $this->have_default_value;
	}

	public static function init(string $name, bool $nullable): static
	{
		return new self($name, $nullable);
	}

}
