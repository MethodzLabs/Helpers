<?php

namespace Methodz\Helpers\Geolocation;

use Exception;
use Methodz\Helpers\Database\Database;

class Language
{
	private ?int $id;
	private int $country_id;
	private string $name;
	private string $iso_code_2;

	private ?Country $country = null;

	private function __construct(int $country_id, string $name, string $iso_code_2, ?int $id = null)
	{
		$this->id = $id;
		$this->country_id = $country_id;
		$this->name = $name;
		$this->iso_code_2 = $iso_code_2;
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

	public function getIsoCode2(): string
	{
		return $this->iso_code_2;
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

	/**
	 * @throws Exception
	 */
	public function save(): self
	{
		if ($this->getId() === null) {
			$result = Database::insert(
				table: "language",
				data: [
					'country_id' => $this->country_id,
					'name' => $this->name,
					'iso_code_2' => $this->iso_code_2,
				]
			);
			if ($result->isOK()) {
				$this->setId(Database::getLastInsertId());
			} else {
				throw new Exception("Object " . self::class . " can't be save");
			}
		}
		return $this;
	}

	/**
	 * @param int      $country_id
	 * @param string   $name
	 * @param string   $iso_code_2
	 * @param int|null $id
	 *
	 * @return self
	 */
	public static function init(int $country_id, string $name, string $iso_code_2, ?int $id = null): self
	{
		return new self($country_id, $name, $iso_code_2, $id);
	}

	/**
	 * @param int $id - The id of city
	 *
	 * @return self|null
	 */
	public static function findById(int $id): ?self
	{
		$data = Database::getRow("SELECT * FROM `language` WHERE `language`.`id`=:id", [':id' => $id]);
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
			iso_code_2: $data['iso_code_2'],
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
