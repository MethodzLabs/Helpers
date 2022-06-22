<?php

namespace Methodz\Helpers\Geolocation;

use Exception;
use Methodz\Helpers\Database\Database;

class City
{
	private ?int $id;
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

	public function getId(): ?int
	{
		return $this->id;
	}

	private function setId(int $id): self
	{
		$this->id = $id;

		return $this;
	}

	public function getCountryId(): int
	{
		return $this->country_id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getCoordinate(): Coordinate
	{
		return $this->coordinate;
	}

	/**
	 * @return Country
	 */
	public function getCountry(): Country
	{
		if ($this->country === null) {
			$this->country = Country::getWhereIdEquals($this->getCountryId());
		}

		return $this->country;
	}

	/**
	 * @throws Exception
	 */
	public function save(): self
	{
		if ($this->getId() === null) {
			$result = Database::insert(
				table: "city",
				data: [
					'country_id' => $this->country_id,
					'name' => $this->name,
					'latitude' => $this->coordinate->getLatitude(),
					'longitude' => $this->coordinate->getLongitude(),
				]
			);
			if ($result->isOK()) {
				$this->setId(Database::getLastInsertId());
			} else {
				throw new Exception("Object " . self::class . " can't be save");
			}
		} else {
			$result = Database::update(
				table: "city",
				data: [
					'country_id' => $this->country_id,
					'name' => $this->name,
					'latitude' => $this->coordinate->getLatitude(),
					'longitude' => $this->coordinate->getLongitude(),
				],
				where: "`id`=:id",
				where_params: [
					':id' => $this->getId(),
				]
			);
			if (!$result->isOK()) {
				throw new Exception("Object " . self::class . " can't be save");
			}
		}
		return $this;
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
	 * @param int $country_id - The id of country
	 *
	 * @return self[]
	 */
	public static function getCitiesForCountryId(int $country_id): array
	{
		$data = Database::getData("SELECT * FROM `city` WHERE `city`.`country_id`=:country_id", [':country_id' => $country_id]);
		$result = [];
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult());
		}
		return $result;
	}

	/**
	 * @param string $iso_code_2 - Code ISO of country
	 *
	 * @return self[]
	 */
	public static function getCitiesForCountryIsoCode2(string $iso_code_2): array
	{
		$data = Database::getData("SELECT * FROM `city` WHERE `city`.`iso_code_2`=:iso_code_2", [':iso_code_2' => $iso_code_2]);
		$result = [];
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult());
		}
		return $result;
	}


	/**
	 * @param int $id - The id of city
	 *
	 * @return self|null
	 */
	public static function getCityById(int $id): ?self
	{
		$data = Database::getRow("SELECT * FROM `city` WHERE `city`.`id`=:id", [':id' => $id]);
		$result = null;
		if ($data->isOK()) {
			$result = self::arrayToObject($data->getResult());
		}
		return $result;
	}

	public static function arrayToObject(array $data): self
	{
		return self::init(
			country_id: $data['country_id'],
			name: $data['name'],
			latitude: $data['latitude'],
			longitude: $data['longitude'],
			id: $data['id']
		);
	}

	/**
	 * @param array $data
	 *
	 * @return self[]
	 */
	public static function arrayToObjects(array $data): array
	{
		$result = [];
		foreach ($data as $row) {
			$result[] = self::arrayToObject($row);
		}
		return $result;
	}
}
