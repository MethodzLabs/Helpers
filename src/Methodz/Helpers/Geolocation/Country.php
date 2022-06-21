<?php

namespace Methodz\Helpers\Geolocation;

use Methodz\Helpers\Database\Database;

class Country
{
	private int $id;
	private string $name;
	private string $iso_code_2;
	private string $iso_code_3;

	/**
	 * @var City[]|null
	 */
	private ?array $cities = null;

	private function __construct(int $id, string $name, string $iso_code_2, string $iso_code_3)
	{
		$this->id = $id;
		$this->name = $name;
		$this->iso_code_2 = $iso_code_2;
		$this->iso_code_3 = $iso_code_3;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getIsoCode2(): string
	{
		return $this->iso_code_2;
	}

	public function getIsoCode3(): string
	{
		return $this->iso_code_3;
	}

	public function getCities(bool $refresh = false): array
	{
		if ($refresh || $this->cities === null) {
			$this->cities = City::getCitiesForCountryId($this->getId());
		}

		return $this->cities;
	}

	/**
	 * @param int    $id
	 * @param string $name
	 * @param string $iso_code_2
	 * @param string $iso_code_3
	 *
	 * @return self
	 */
	public static function init(int $id, string $name, string $iso_code_2, string $iso_code_3): self
	{
		return new self($id, $name, $iso_code_2, $iso_code_3);
	}

	/**
	 * @return self[]
	 */
	public static function getAll(): array
	{
		$data = Database::getData("SELECT * FROM `country`");
		$result = [];
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult());
		}
		return $result;
	}

	/**
	 * @return self[]
	 */
	public static function getAllWhereNameLike(string $name): array
	{
		$data = Database::getData("SELECT * FROM `country` WHERE `country`.`name` LIKE :name", [':name' => $name]);
		$result = [];
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult());
		}
		return $result;
	}

	/**
	 * @param string $iso_code_2
	 *
	 * @return Country|null
	 */
	public static function getWhereIsoCode2Equals(string $iso_code_2): ?self
	{
		$data = Database::getRow("SELECT * FROM `country` WHERE `country`.`name` LIKE :name", [':name' => $iso_code_2]);
		$result = null;
		if ($data->isOK()) {
			$result = self::arrayToObject($data->getResult());
		}
		return $result;
	}

	public static function arrayToObject(array $data): self
	{
		return self::init(
			id: $data['id'],
			name: $data['name'],
			iso_code_2: $data['iso_code_2'],
			iso_code_3: $data['iso_code_3']
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
