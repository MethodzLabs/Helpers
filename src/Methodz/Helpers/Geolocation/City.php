<?php

namespace Methodz\Helpers\Geolocation;

use Methodz\Helpers\Models\Model;

class City extends Model
{
	public const _TABLE = "city";
	public const _ID = "id";
	public const _NAME = "name";
	public const _COUNTRY_ID = "country_id";
	public const _LATITUDE = "latitude";
	public const _LONGITUDE = "longitude";

	private int $country_id;
	private string $name;
	private Coordinate $coordinate;

	private ?Country $country = null;

	private function __construct(int $country_id, string $name, Coordinate $coordinate, ?int $id = null)
	{
		$this->id = $id;
		$this->country_id = $country_id;
		$this->name = $name;
		$this->coordinate = $coordinate;
	}

	public function getCountryId(): int
	{
		return $this->country_id;
	}

	public function setCountryId(int $country_id): self
	{
		$this->country_id = $country_id;

		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getCoordinate(): Coordinate
	{
		return $this->coordinate;
	}

	public function setCoordinate(Coordinate $coordinate): self
	{
		$this->coordinate = $coordinate;

		return $this;
	}

	/**
	 * @return Country
	 */
	public function getCountry(): Country
	{
		if ($this->country === null) {
			$this->country = Country::findById($this->getCountryId());
		}

		return $this->country;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				self::_COUNTRY_ID => $this->country_id,
				self::_NAME => $this->name,
				self::_LATITUDE => $this->coordinate->getLatitude(),
				self::_LONGITUDE => $this->coordinate->getLongitude(),
			]);
	}

	/**
	 * @param int      $country_id
	 * @param string   $name
	 * @param float    $latitude
	 * @param float    $longitude
	 * @param int|null $id
	 *
	 * @return self
	 */
	public static function init(int $country_id, string $name, float $latitude, float $longitude, ?int $id = null): self
	{
		return new self($country_id, $name, Coordinate::init($latitude, $longitude), $id);
	}

	/**
	 * @param string $name
	 *
	 * @return self[]|null
	 */
	public static function findAllByName(string $name): ?array
	{
		return self::findAllBy(self::_NAME, $name);
	}

	/**
	 * @param int $country_id
	 *
	 * @return self[]|null
	 */
	public static function findAllByCountryId(int $country_id): ?array
	{
		return self::findAllBy(self::_COUNTRY_ID, $country_id);
	}

	public static function findById(int $id): ?static
	{
		return parent::findById($id);
	}

	public static function arrayToObject(array $data): static
	{
		return self::init(
			country_id: $data[self::_COUNTRY_ID],
			name: $data[self::_NAME],
			latitude: $data[self::_LATITUDE],
			longitude: $data[self::_LONGITUDE],
			id: $data[self::_ID] ?? null,
		);
	}
}
