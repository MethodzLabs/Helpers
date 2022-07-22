<?php

namespace Methodz\Models\Helpers;

use Methodz\Helpers\Type\_Float;
use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\_String;
use function Methodz\Helpers\Type\_float;
use function Methodz\Helpers\Type\_int;
use function Methodz\Helpers\Type\_string;

class City extends \Methodz\Helpers\Models\Model
{

	use \Methodz\Helpers\Models\CommonTrait;

	const _DATABASE = \Methodz\Helpers\Database\HelpersDatabase::class;
	const _TABLE = "city";
	const _ID = "id";
	const _COUNTRY_ID = "country_id";
	const _NAME = "name";
	const _LATITUDE = "latitude";
	const _LONGITUDE = "longitude";

	private _Int $country_id;
	private _String $name;
	private _Float $latitude;
	private _Float $longitude;

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

	public function getName(): _String
	{
		return $this->name;
	}

	public function setName(_String $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getLatitude(): _Float
	{
		return $this->latitude;
	}

	public function setLatitude(_Float $latitude): static
	{
		$this->latitude = $latitude;

		return $this;
	}

	public function getLongitude(): _Float
	{
		return $this->longitude;
	}

	public function setLongitude(_Float $longitude): static
	{
		$this->longitude = $longitude;

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
				static::_NAME => $this->name->getValue(),
				static::_LATITUDE => $this->latitude->getValue(),
				static::_LONGITUDE => $this->longitude->getValue(),
			]);
	}


	public static function init(_Int $country_id, _String $name, _Float $latitude, _Float $longitude, ?_Int $id = null): static
	{
		$_object = new static();

		$_object->id = $id;
		$_object->country_id = $country_id;
		$_object->name = $name;
		$_object->latitude = $latitude;
		$_object->longitude = $longitude;

		return $_object;
	}

	public static function fromArray(array $data): static
	{
		return static::init(
			country_id: _int($data[static::_COUNTRY_ID]),
			name: _string($data[static::_NAME]),
			latitude: _float($data[static::_LATITUDE]),
			longitude: _float($data[static::_LONGITUDE]),
			id: $data[static::_ID] ?? null,
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
