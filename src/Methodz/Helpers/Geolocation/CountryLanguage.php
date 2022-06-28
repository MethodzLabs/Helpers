<?php

namespace Methodz\Helpers\Geolocation;

use Methodz\Helpers\Models\Model;

class CountryLanguage extends Model
{
	public const _TABLE = "country_language";
	public const _ID = "id";
	public const _COUNTRY_ID = "country_id";
	public const _LANGUAGE_ID = "language_id";

	private int $country_id;
	private int $language_id;

	private ?Country $country = null;
	private ?Language $language = null;

	private function __construct(int $country_id, int $language_id, ?int $id)
	{
		$this->id = $id;
		$this->country_id = $country_id;
		$this->language_id = $language_id;
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

	public function getLanguageId(): int
	{
		return $this->language_id;
	}

	public function setLanguageId(int $language_id): self
	{
		$this->language_id = $language_id;

		return $this;
	}

	public function getCountry(): Country
	{
		if ($this->country === null) {
			$this->country = Country::findById($this->getCountryId());
		}

		return $this->country;
	}

	public function getLanguage(): Language
	{
		if ($this->language === null) {
			$this->language = Language::findById($this->getLanguageId());
		}

		return $this->language;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				self::_COUNTRY_ID => $this->country_id,
				self::_LANGUAGE_ID => $this->language_id,
			]);
	}

	/**
	 * @param int      $country_id
	 * @param int      $language_id
	 * @param int|null $id
	 *
	 * @return self
	 */
	public static function init(int $country_id, int $language_id, ?int $id): self
	{
		return new self($country_id, $language_id, $id);
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

	/**
	 * @param int $language_id
	 *
	 * @return self[]|null
	 */
	public static function findAllByLanguageId(int $language_id): ?array
	{
		return self::findAllBy(self::_LANGUAGE_ID, $language_id);
	}

	public static function arrayToObject(array $data): static
	{
		return self::init(
			country_id: $data[self::_COUNTRY_ID],
			language_id: $data[self::_LANGUAGE_ID],
			id: $data[self::_ID] ?? null,
		);
	}
}
