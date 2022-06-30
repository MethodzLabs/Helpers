<?php

namespace Methodz\Helpers\Geolocation;

use Methodz\Helpers\Models\Model;

class SearchEngine extends Model
{
	public const _TABLE = "search_engine";
	public const _ID = "id";
	public const _COUNTRY_ID = "country_id";
	public const _URL = "url";

	private int $country_id;
	private string $url;

	private ?Country $country = null;

	private function __construct(int $country_id, string $url, ?int $id)
	{
		$this->id = $id;
		$this->country_id = $country_id;
		$this->url = $url;
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

	public function getUrl(): string
	{
		return $this->url;
	}

	public function setUrl(string $url): self
	{
		$this->url = $url;

		return $this;
	}

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
				self::_URL => $this->url,
			]);
	}

	/**
	 * @param int      $country_id
	 * @param string   $url
	 * @param int|null $id
	 *
	 * @return self
	 */
	public static function init(int $country_id, string $url, ?int $id): self
	{
		return new self($country_id, $url, $id);
	}

	public static function findById(int $id): ?static
	{
		return parent::findById($id);
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

	public static function arrayToObject(array $data): static
	{
		return self::init(
			country_id: $data[self::_COUNTRY_ID],
			url: $data[self::_URL],
			id: $data[self::_ID] ?? null,
		);
	}
}
