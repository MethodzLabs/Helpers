<?php

namespace Methodz\Helpers\Accessors;

use JetBrains\PhpStorm\Pure;
use Methodz\Helpers\Date\DateTime;

class Cookie
{
	private string $name;
	private int $maxTime = 0;
	private string $domain = "";

	private function __construct(string $name)
	{
		$this->name = $name;
	}

	#[Pure] public static function create(string $name): self
	{
		return new self($name);
	}

	public static function get(string $name): mixed
	{
		if (!self::exist($name)) {
			return null;
		}
		return json_decode($_COOKIE[$name]);
	}

	#[Pure] public static function exist(string $name): bool
	{
		return array_key_exists($name, $_COOKIE);
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
}
