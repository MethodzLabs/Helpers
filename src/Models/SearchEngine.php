<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Database\HelpersDatabase;
use Methodz\Helpers\Database\Query\QuerySelect;

class SearchEngine extends Model
{
	const _DATABASE = HelpersDatabase::class;
	const _TABLE = "search_engine";
	const _ID = "id";
	const _COUNTRY_ID = "country_id";
	const _URL = "url";
	const _TYPE = "type";

	private int $country_id;
	private string $url;
	private SearchEngineTypeEnum $type;

	private ?Country $country = null;

	private function __construct(int $country_id, string $url, SearchEngineTypeEnum $type, ?int $id)
	{
		$this->id = $id;
		$this->country_id = $country_id;
		$this->url = $url;
		$this->type = $type;
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

	public function getType(): SearchEngineTypeEnum
	{
		return $this->type;
	}

	public function setType(SearchEngineTypeEnum $type): self
	{
		$this->type = $type;

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
				self::_TYPE => $this->type->value,
			]);
	}

	/**
	 * @param int                  $country_id
	 * @param string               $url
	 * @param SearchEngineTypeEnum $type
	 * @param int|null             $id
	 *
	 * @return self
	 */
	public static function init(int $country_id, string $url, SearchEngineTypeEnum $type, ?int $id): self
	{
		return new self($country_id, $url, $type, $id);
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

	/**
	 * @param QuerySelect $query
	 *
	 * @return self[]|null
	 */
	public static function findAllByQuery(QuerySelect $query): ?array
	{
		return parent::findAllByQuery($query);
	}

	/**
	 * @param QuerySelect $query
	 *
	 * @return self|null
	 */
	public static function findByQuery(QuerySelect $query): ?static
	{
		return parent::findByQuery($query);
	}


	public static function arrayToObject(array $data): static
	{
		return self::init(
			country_id: $data[self::_COUNTRY_ID],
			url: $data[self::_URL],
			type: SearchEngineTypeEnum::from($data[self::_TYPE]),
			id: $data[self::_ID] ?? null,
		);
	}
}
