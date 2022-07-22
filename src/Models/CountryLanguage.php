<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Type\_Int;
use function Methodz\Helpers\Type\_int;

class CountryLanguage extends Structure\Model
{

	use Structure\CommonTrait;

	const _DATABASE = \Methodz\Helpers\Database\HelpersDatabase::class;
	const _TABLE = "country_language";
	const _ID = "id";
	const _COUNTRY_ID = "country_id";
	const _LANGUAGE_ID = "language_id";

	private _Int $country_id;
	private _Int $language_id;

	private ?Country $country = null;
	private ?Language $language = null;


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

	public function getLanguageId(): _Int
	{
		return $this->language_id;
	}

	public function setLanguageId(_Int $language_id): static
	{
		$this->language_id = $language_id;

		return $this;
	}

	public function getCountry(): Country
	{
		if ($this->country === null) {
			$this->country = Country::findBy(Country::_ID, $this->country_id);
		}
		return $this->country;
	}

	public function getLanguage(): Language
	{
		if ($this->language === null) {
			$this->language = Language::findBy(Language::_ID, $this->language_id);
		}
		return $this->language;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				static::_COUNTRY_ID => $this->country_id->getValue(),
				static::_LANGUAGE_ID => $this->language_id->getValue(),
			]);
	}


	public static function init(_Int $country_id, _Int $language_id, ?_Int $id = null): static
	{
		$_object = new static();

		$_object->id = $id;
		$_object->country_id = $country_id;
		$_object->language_id = $language_id;

		return $_object;
	}

	public static function fromArray(array $data): static
	{
		return static::init(
			country_id: _int($data[static::_COUNTRY_ID]),
			language_id: _int($data[static::_LANGUAGE_ID]),
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
