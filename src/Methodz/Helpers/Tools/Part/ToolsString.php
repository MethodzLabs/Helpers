<?php

namespace Methodz\Helpers\Tools\Part;

use Exception;
use Methodz\Helpers\Date\DateTime;
use Methodz\Helpers\Exceptions\NotBooleanException;
use Methodz\Helpers\Exceptions\NotDateTimeException;
use Methodz\Helpers\Exceptions\NotJsonArrayException;
use Methodz\Helpers\Exceptions\NotNullException;

class ToolsString
{
	private string $str;

	private function __construct(string $str) { $this->str = $str; }

	public function asNumber(): ToolsNumber
	{
		return ToolsNumber::init($this->str);
	}

	/**
	 * @throws NotNullException
	 */
	public function asNull(): mixed
	{
		if ($this->str !== "NULL" && $this->str !== "null") {
			throw new NotNullException($this->str);
		}
		return null;
	}

	public function asString(): string
	{
		return $this->str;
	}

	/**
	 * @throws NotBooleanException
	 */
	public function asBoolean(): bool
	{
		if (in_array($this->str, ["TRUE", "True", "true", "1"])) {
			return true;
		}
		if (in_array($this->str, ["FALSE", "False", "false", "0"])) {
			return false;
		}
		throw new NotBooleanException($this->str);
	}

	/**
	 * @throws NotDateTimeException
	 */
	public function asDateTime(): DateTime
	{
		try {
			if (($datetime = new DateTime($this->str))->isValidDateTime()) {
				return $datetime;
			}
		} catch (Exception $e) {
		}
		throw new NotDateTimeException($this->str);
	}

	/**
	 * @throws NotJsonArrayException
	 */
	public function asJsonArray(): array
	{
		try {
			return json_decode($this->str);
		} catch (Exception $e) {
		}

		throw new NotJsonArrayException($this->str);
	}

	public static function init(string $str): self
	{
		return new self($str);
	}
}
