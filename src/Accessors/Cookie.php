<?php

namespace Accessors;

use Date\DateTime;
use function Methodz\Helpers\Accessors\setcookie;

class Cookie
{
	private string $name;
	private int $maxTime = 0;
	private string $domain = "";

	private function __construct(string $name)
	{
		$this->name = $name;
	}

	public function setDatetimeExpire(DateTime $dateTime): self
	{
		$this->maxTime = $dateTime->getTimestamp();
		return $this;
	}

	public function setTimeExpire(int $time): self
	{
		$this->maxTime = $time;
		return $this;
	}

	public function setDomain(string $domain): self
	{
		$this->domain = $domain;
		return $this;
	}

	public function setExpireInTimePlusSeconds(int $seconds): self
	{
		$this->maxTime = time() + $seconds;
		return $this;
	}

	public function save(mixed $value): bool
	{
		return setcookie($this->name, json_encode($value), $this->maxTime, path: '', secure: false, domain: $this->domain);
	}

	public function delete(): bool
	{
		return setcookie($this->name, "", 1);
	}


	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public static function create(string $name): self
	{
		return new self($name);
	}

	/**
	 * Returns the value of the cookie or null for the name passed in parameters
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function get(string $name): mixed
	{
		if (!self::exist($name)) {
			return null;
		}
		return json_decode($_COOKIE[$name]);
	}

	/**
	 * Check if a cookie exists for the name
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function exist(string $name): bool
	{
		return array_key_exists($name, $_COOKIE);
	}
}
