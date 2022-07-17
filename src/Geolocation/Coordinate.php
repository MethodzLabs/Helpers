<?php

namespace Geolocation;

use Models\CommonTrait;

class Coordinate
{
	use CommonTrait;

	private float $latitude;
	private float $longitude;

	private function __construct(float $latitude, float $longitude)
	{
		$this->latitude = $latitude;
		$this->longitude = $longitude;
	}

	/**
	 * @return float
	 */
	public function getLatitude(): float
	{
		return $this->latitude;
	}

	/**
	 * @param float $latitude
	 *
	 * @return Coordinate
	 */
	public function setLatitude(float $latitude): self
	{
		$this->latitude = $latitude;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLongitude(): float
	{
		return $this->longitude;
	}

	/**
	 * @param float $longitude
	 *
	 * @return Coordinate
	 */
	public function setLongitude(float $longitude): self
	{
		$this->longitude = $longitude;

		return $this;
	}


	/**
	 * @param float $latitude
	 * @param float $longitude
	 *
	 * @return self
	 */
	public static function init(float $latitude, float $longitude): self
	{
		return new self($latitude, $longitude);
	}

}
