<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Models\Part\LanguageData;

class Language extends Model
{
	public const _TABLE = "language";
	public const _ID = "id";
	public const _NAME = "name";
	public const _ISO_CODE_2 = "iso_code_2";
	public const _ISO_CODE_3 = "iso_code_3";
	public const _DATA = "data";

	private string $name;
	private string $iso_code_2;
	private ?string $iso_code_3;
	private LanguageData $data;

	/**
	 * @var CountryLanguage[]|null
	 */
	private ?array $countryLanguages = null;

	private function __construct(string $name, string $iso_code_2, ?string $iso_code_3, LanguageData $data, ?int $id = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->iso_code_2 = $iso_code_2;
		$this->iso_code_3 = $iso_code_3;
		$this->data = $data;
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

	public function getData(): LanguageData
	{
		return $this->data;
	}

	public function setData(LanguageData $data): self
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @return CountryLanguage[]
	 */
	public function getCountryLanguages(): array
	{
		if ($this->countryLanguages === null) {
			$this->countryLanguages = CountryLanguage::findAllByLanguageId($this->getId());
		}

		return $this->countryLanguages;
	}


	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				self::_NAME => $this->name,
				self::_ISO_CODE_2 => $this->iso_code_2,
				self::_ISO_CODE_3 => $this->iso_code_3,
				self::_DATA => json_encode($this->data),
			]);
	}

	/**
	 * @param string       $name
	 * @param string       $iso_code_2
	 * @param string|null  $iso_code_3
	 * @param LanguageData $data
	 * @param int|null     $id
	 *
	 * @return self
	 */
	public static function init(string $name, string $iso_code_2, ?string $iso_code_3, LanguageData $data, ?int $id = null): self
	{
		return new self($name, $iso_code_2, $iso_code_3, $data, $id);
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

	public static function arrayToObject(array $data): static
	{
		return self::init(
			name: $data[self::_NAME],
			iso_code_2: $data[self::_ISO_CODE_2],
			iso_code_3: $data[self::_ISO_CODE_3],
			data: LanguageData::init($data[self::_DATA]),
			id: $data[self::_ID] ?? null
		);
	}
}
