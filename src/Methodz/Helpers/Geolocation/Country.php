<?php

namespace Methodz\Helpers\Geolocation;

use Methodz\Helpers\Models\Model;

class Country extends Model
{
	public const _TABLE = "country";
	public const _ID = "id";
	public const _NAME = "name";
	public const _ISO_CODE_2 = "iso_code_2";
	public const _ISO_CODE_3 = "iso_code_3";
	public const _ISO_CODE_NUMERIC = "iso_code_numeric";

	private string $name;
	private string $iso_code_2;
	private ?string $iso_code_3;
	private ?int $iso_code_numeric;

	/**
	 * @var City[]|null
	 */
	private ?array $cities = null;

	/**
	 * @var CountryLanguage[]|null
	 */
	private ?array $countryLanguages = null;

	private function __construct(int $id, string $name, string $iso_code_2, ?string $iso_code_3, ?int $iso_code_numeric)
	{
		$this->id = $id;
		$this->name = $name;
		$this->iso_code_2 = $iso_code_2;
		$this->iso_code_3 = $iso_code_3;
		$this->iso_code_numeric = $iso_code_numeric;
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

	public function getIsoCode2(): string
	{
		return $this->iso_code_2;
	}

	public function setIsoCode2(string $iso_code_2): self
	{
		$this->iso_code_2 = $iso_code_2;

		return $this;
	}

	public function getIsoCode3(): ?string
	{
		return $this->iso_code_3;
	}

	public function setIsoCode3(?string $iso_code_3): self
	{
		$this->iso_code_3 = $iso_code_3;

		return $this;
	}

	public function getIsoCodeNumeric(): ?int
	{
		return $this->iso_code_numeric;
	}

	public function setIsoCodeNumeric(?int $iso_code_numeric): self
	{
		$this->iso_code_numeric = $iso_code_numeric;

		return $this;
	}

	public function getCities(bool $refresh = false): array
	{
		if ($refresh || $this->cities === null) {
			$this->cities = City::findAllByCountryId($this->getId());
		}

		return $this->cities;
	}

	/**
	 * @return CountryLanguage[]
	 */
	public function getCountryLanguages(): array
	{
		if ($this->countryLanguages === null) {
			$this->countryLanguages = CountryLanguage::findAllByCountryId($this->getId());
		}

		return $this->countryLanguages;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				self::_NAME => $this->name,
				self::_ISO_CODE_2 => $this->iso_code_2,
				self::_ISO_CODE_3 => $this->iso_code_3,
				self::_ISO_CODE_NUMERIC => $this->iso_code_numeric,
			]);
	}

	/**
	 * @param int         $id
	 * @param string      $name
	 * @param string      $iso_code_2
	 * @param string|null $iso_code_3
	 * @param int|null    $iso_code_numeric
	 *
	 * @return self
	 */
	public static function init(int $id, string $name, string $iso_code_2, ?string $iso_code_3, ?int $iso_code_numeric): self
	{
		return new self($id, $name, $iso_code_2, $iso_code_3, $iso_code_numeric);
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

	public static function findById(int $id): ?static
	{
		return parent::findById($id);
	}

	public static function findByIsoCode2(string $iso_code_2): ?self
	{
		return self::findBy(self::_ISO_CODE_2, $iso_code_2);
	}

	public static function findByIsoCode3(string $iso_code_3): ?self
	{
		return self::findBy(self::_ISO_CODE_3, $iso_code_3);
	}

	public static function findByIsoCodeNumeric(string $iso_code_numeric): ?self
	{
		return self::findBy(self::_ISO_CODE_3, $iso_code_numeric);
	}

	public static function arrayToObject(array $data): static
	{
		return self::init(
			id: $data[self::_ID],
			name: $data[self::_NAME],
			iso_code_2: $data[self::_ISO_CODE_2],
			iso_code_3: $data[self::_ISO_CODE_3],
			iso_code_numeric: $data[self::_ISO_CODE_NUMERIC]
		);
	}
}
