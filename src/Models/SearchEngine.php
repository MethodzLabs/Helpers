<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Models\Enum\SearchEngineTypeEnum;
use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\_String;
use function Methodz\Helpers\Type\_int;
use function Methodz\Helpers\Type\_string;

class SearchEngine extends Structure\Model
{

	use Structure\CommonTrait;

	const _DATABASE = \Methodz\Helpers\Database\HelpersDatabase::class;
	const _TABLE = "search_engine";
	const _ID = "id";
	const _COUNTRY_ID = "country_id";
	const _URL = "url";
	const _TYPE = "type";

	private _Int $country_id;
	private _String $url;
	private SearchEngineTypeEnum $type;

	private ?Country $country = null;


	private function __construct() { }


	public function getId(): ?_Int
	{
		return $this->id;
	}

	public function setId(?_Int $id): static
	{
		$this->id = $id;

		return $this;
	}

	public function getCountryId(): _Int
	{
		return $this->country_id;
	}

	public function setCountryId(_Int $country_id): static
	{
		$this->country_id = $country_id;

		return $this;
	}

	public function getUrl(): _String
	{
		return $this->url;
	}

	public function setUrl(_String $url): static
	{
		$this->url = $url;

		return $this;
	}

	public function getType(): SearchEngineTypeEnum
	{
		return $this->type;
	}

	public function setType(SearchEngineTypeEnum $type): static
	{
		$this->type = $type;

		return $this;
	}

	public function getCountry(): Country
	{
		if ($this->country === null) {
			$this->country = Country::findBy(Country::_ID, $this->country_id);
		}
		return $this->country;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				static::_COUNTRY_ID => $this->country_id->getValue(),
				static::_URL => $this->url->getValue(),
				static::_TYPE => $this->type->value,
			]);
	}


	public static function init(_Int $country_id, _String $url, SearchEngineTypeEnum $type, ?_Int $id = null): static
	{
		$_object = new static();

		$_object->id = $id;
		$_object->country_id = $country_id;
		$_object->url = $url;
		$_object->type = $type;

		return $_object;
	}

	public static function fromArray(array $data): static
	{
		return static::init(
			country_id: _int($data[static::_COUNTRY_ID]),
			url: _string($data[static::_URL]),
			type: SearchEngineTypeEnum::from($data[static::_TYPE]),
			id: array_key_exists(static::_ID, $data) ? \Methodz\Helpers\Type\_int($data[static::_ID]) : null,
		)->set_data($data);
	}


	public static function findById(_Int $id): ?static
	{
		return parent::findById($id);
	}

	/**
	 * @return static[]|null
	 */
	public static function findAll(bool $idAsKey = false): ?array
	{
		return parent::findAll($idAsKey);
	}

	public static function findByQuery(\Methodz\Helpers\Database\Query\QuerySelect $query): ?static
	{
		return parent::findByQuery($query);
	}

	/**
	 * @return static[]|null
	 */
	public static function findAllByQuery(\Methodz\Helpers\Database\Query\QuerySelect $query): ?array
	{
		return parent::findAllByQuery($query);
	}
}
