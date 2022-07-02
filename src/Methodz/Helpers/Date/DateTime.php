<?php

namespace Methodz\Helpers\Date;

use DateTimeZone;

/**
 * @author Thomas FONTAINE--TUFFERY
 */
class DateTime extends \DateTime
{
	private string $datetime;

	public function __construct(string $datetime = 'now')
	{
		parent::__construct($datetime);
		$this->datetime = $datetime;
	}

	public function formatMin(string $format = 'Y-m-d'): string
	{
		return parent::format($format);
	}

	public function formatMax(string $format = 'Y-m-d H:i:s'): string
	{
		return parent::format($format);
	}

	public function formatFrenchMin(string $format = 'd/m/Y'): string
	{
		return parent::format($format);
	}

	public function formatFrenchMax(string $format = 'H:i:s d/m/Y'): string
	{
		return parent::format($format);
	}

	public function isValidDateTime(): bool
	{
		if (in_array($this->formatMax(), ["0000-01-01 00:00:00", "0000-00-00 00:00:00"])) {
			return false;
		}
		if (in_array($this->getTimestamp(), [-1, 0])) {
			return false;
		}
		return true;
	}

	public function isBefore(string|DateTime $datetime): bool
	{
		if (is_string($datetime)) {
			$datetime = new self($datetime);
		}
		return $this->getTimestamp() < $datetime->getTimestamp();
	}

	public function isAfter(string|DateTime $datetime): bool
	{
		if (is_string($datetime)) {
			$datetime = new self($datetime);
		}
		return $this->getTimestamp() > $datetime->getTimestamp();
	}

	public function equals(string|DateTime $datetime): bool
	{
		if (is_string($datetime)) {
			$datetime = new self($datetime);
		}
		return $this->getTimestamp() == $datetime->getTimestamp();
	}

	public function setDate(int $year, int $month, int $day): self
	{
		parent::setDate($year, $month, $day);
		return $this;
	}

	public function setTime(int $hour = 0, int $minute = 0, int $second = 0, int $microsecond = 0): self
	{
		parent::setTime($hour, $minute, $second, $microsecond);
		return $this;
	}

	public function setTimestamp(int $timestamp): self
	{
		parent::setTimestamp($timestamp);
		return $this;
	}

	public function __toString()
	{
		return $this->formatMax();
	}

	public static function now(): self
	{
		return new self();
	}

	public static function createFromFormat(string $format, string $datetime, DateTimeZone|null $timezone = null): self
	{
		return self::createFromTimestamp(\DateTime::createFromFormat($format, $datetime, $timezone)->getTimestamp());
	}

	public static function createFromTimestamp(int $timestamp): self
	{
		return self::now()->setTimestamp($timestamp);
	}
}
