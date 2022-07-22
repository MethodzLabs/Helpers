<?php

namespace Methodz\Helpers\Type;

use DateTime;
use DateTimeZone;
use Methodz\Helpers\Type\Enum\_DateTimeFormatEnum;

/**
 * @author Thomas FONTAINE--TUFFERY
 */
class _DateTime extends _Any
{
	private DateTime $value;

	private function __construct(DateTime $value)
	{
		$this->value = $value;
	}

	public static function init(int|string|DateTime $value = "now", string|_DateTimeFormatEnum $format = _DateTimeFormatEnum::DATETIME): static
	{
		$datetime = new DateTime();

		if ($value !== "now") {
			if (gettype($value) === "object") {
				$datetime = $value;
			} else {
				if (is_int($value)) {
					$datetime = (new DateTime())->setTimestamp($value);
				} else {
					if (gettype($format) === "object") {
						$format = $format->value;
					}
					$datetime = DateTime::createFromFormat($format, $value);
				}
			}
		}
		return new static($datetime);
	}

	public function __toString(): string
	{
		return $this->formatAsDateTime();
	}


	public function format(string|_DateTimeFormatEnum $format): string
	{
		if (!is_string($format)) {
			$format = $format->value;
		}
		return $this->value->format($format);
	}

	public function formatAsDate(): string
	{
		return $this->value->format(_DateTimeFormatEnum::DATE->value);
	}

	public function formatAsDateTime(): string
	{
		return $this->value->format(_DateTimeFormatEnum::DATETIME->value);
	}

	public function formatAsDateFrench(): string
	{
		return $this->value->format(_DateTimeFormatEnum::DATE_FRENCH->value);
	}

	public function formatAsDateTimeFrench(): string
	{
		return $this->value->format(_DateTimeFormatEnum::DATETIME_FRENCH->value);
	}

	public function isValid(): bool
	{
		if (in_array($this->formatAsDateTime(), ["0000-01-01 00:00:00", "0000-00-00 00:00:00"])) {
			return false;
		}
		if (in_array($this->getTimestamp(), [-1, 0])) {
			return false;
		}
		return true;
	}

	public function isBefore(string|_DateTime $datetime, string|_DateTimeFormatEnum $format = _DateTimeFormatEnum::DATETIME): bool
	{
		if (is_string($datetime)) {
			$datetime = static::init($datetime, $format);
		}
		return $this->getTimestamp() < $datetime->getTimestamp();
	}

	public function isAfter(string|_DateTime $datetime, string|_DateTimeFormatEnum $format = _DateTimeFormatEnum::DATETIME): bool
	{
		if (is_string($datetime)) {
			$datetime = static::init($datetime, $format);
		}
		return $this->getTimestamp() > $datetime->getTimestamp();
	}

	public function equals(string|DateTime|_DateTime $datetime, string|_DateTimeFormatEnum $format = _DateTimeFormatEnum::DATETIME): bool
	{
		if (is_string($datetime)) {
			$datetime = static::init($datetime, $format);
		}
		return $this->getTimestamp() == $datetime->getTimestamp();
	}

	public function setDate(int $year, int $month, int $day): static
	{
		$this->value = $this->value->setDate($year, $month, $day);

		return $this;
	}

	public function setTime(int $hour = 0, int $minute = 0, int $second = 0, int $microsecond = 0): static
	{
		$this->value = $this->value->setTime($hour, $minute, $second, $microsecond);

		return $this;
	}

	public function getTimestamp(): int
	{
		return $this->value->getTimestamp();
	}

	public function setTimestamp(int $timestamp): static
	{
		$this->value = $this->value->setTimestamp($timestamp);

		return $this;
	}
}
