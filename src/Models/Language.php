<?php

namespace Methodz\Helpers\Models;

use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\_String;
use function Methodz\Helpers\Type\_string;

class Language extends Structure\Model
{

	use Structure\CommonTrait;

	const _DATABASE = \Methodz\Helpers\Database\HelpersDatabase::class;
	const _TABLE = "language";
	const _ID = "id";
	const _NAME = "name";
	const _ISO_CODE_2 = "iso_code_2";
	const _ISO_CODE_3 = "iso_code_3";
	const _DATA = "data";

	private _String $name;
	private _String $iso_code_2;
	private ?_String $iso_code_3;
	private _String $data;

	/**
	 * @var CountryLanguage[]|null
	 */
	private ?array $country_language_list = null;


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

	public function getName(): _String
	{
		return $this->name;
	}

	public function setName(_String $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getIsoCode2(): _String
	{
		return $this->iso_code_2;
	}

	public function setIsoCode2(_String $iso_code_2): static
	{
		$this->iso_code_2 = $iso_code_2;

		return $this;
	}

	public function getIsoCode3(): ?_String
	{
		return $this->iso_code_3;
	}

	public function setIsoCode3(?_String $iso_code_3): static
	{
		$this->iso_code_3 = $iso_code_3;

		return $this;
	}

	public function getData(): _String
	{
		return $this->data;
	}

	public function setData(_String $data): static
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @return CountryLanguage[]
	 */
	public function getCountryLanguageList(): array
	{
		if ($this->country_language_list === null) {
			$this->country_language_list = CountryLanguage::findAllBy(CountryLanguage::_LANGUAGE_ID, $this->id);
		}
		return $this->country_language_list;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				static::_NAME => $this->name->getValue(),
				static::_ISO_CODE_2 => $this->iso_code_2->getValue(),
				static::_ISO_CODE_3 => $this->iso_code_3->getValue(),
				static::_DATA => $this->data->getValue(),
			]);
	}


	public static function init(_String $name, _String $iso_code_2, _String $data, ?_String $iso_code_3 = null, ?_Int $id = null): static
	{
		$_object = new static();

		$_object->id = $id;
		$_object->name = $name;
		$_object->iso_code_2 = $iso_code_2;
		$_object->data = $data;
		$_object->iso_code_3 = $iso_code_3;

		return $_object;
	}

	public static function fromArray(array $data): static
	{
		return static::init(
			name: _string($data[static::_NAME]),
			iso_code_2: _string($data[static::_ISO_CODE_2]),
			data: _string($data[static::_DATA]),
			iso_code_3: array_key_exists(static::_ISO_CODE_3, $data) ? _string($data[static::_ISO_CODE_3]) : null,
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
