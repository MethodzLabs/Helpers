<?php

namespace Zaacom\helpers\accessor;

use JetBrains\PhpStorm\Pure;
use Zaacom\helpers\date\DateTime;

class Cookie
{
	private string $name;
	private int $maxTime = 0;

	private function __construct(string $name)
	{
		$this->name = $name;
	}

	#[Pure] public static function create(string $name): self
	{
		return new self($name);
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

	public function setExpireInTimePlusSecond(int $second): self
	{
		$this->maxTime = time() + $second;
		return $this;
	}

	public function save(mixed $value): bool
	{
		return setcookie($this->name, json_encode($value), $this->maxTime);
	}

	public function delete(): bool
	{
		return setcookie($this->name, "", 1);
	}


}
