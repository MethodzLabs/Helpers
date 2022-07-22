<?php

namespace Methodz\Models\Helpers;

use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\_String;
use function Methodz\Helpers\Type\_int;
use function Methodz\Helpers\Type\_string;

class Country extends \Methodz\Helpers\Models\Model
{

	use \Methodz\Helpers\Models\CommonTrait;

	const _DATABASE = \Methodz\Helpers\Database\HelpersDatabase::class;
	const _TABLE = "country";
	const _ID = "id";
	const _NAME = "name";
	const _NAME_ENGLISH = "name_english";
	const _ISO_CODE_2 = "iso_code_2";
	const _ISO_CODE_3 = "iso_code_3";
	const _ISO_CODE_NUMERIC = "iso_code_numeric";
	const _FLAG_SVG_HTML = "flag_svg_html";

	private _String $name;
	private _String $name_english;
	private _String $iso_code_2;
	private ?_String $iso_code_3;
	private ?_Int $iso_code_numeric;
	private _String $flag_svg_html;

	/**
	 * @var CountryLanguage[]|null
	 */
	private ?array $country_language_list = null;
	/**
	 * @var SearchEngine[]|null
	 */
	private ?array $search_engine_list = null;
	/**
	 * @var City[]|null
	 */
	private ?array $city_list = null;


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

	public function getNameEnglish(): _String
	{
		return $this->name_english;
	}

	public function setNameEnglish(_String $name_english): static
	{
		$this->name_english = $name_english;

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

	public function getIsoCodeNumeric(): ?_Int
	{
		return $this->iso_code_numeric;
	}

	public function setIsoCodeNumeric(?_Int $iso_code_numeric): static
	{
		$this->iso_code_numeric = $iso_code_numeric;

		return $this;
	}

	public function getFlagSvgHtml(): _String
	{
		return $this->flag_svg_html;
	}

	public function setFlagSvgHtml(_String $flag_svg_html): static
	{
		$this->flag_svg_html = $flag_svg_html;

		return $this;
	}

	/**
	 * @return CountryLanguage[]
	 */
	public function getCountryLanguageList(): array
	{
		if ($this->country_language_list === null) {
			$this->country_language_list = CountryLanguage::findAllBy(CountryLanguage::_COUNTRY_ID, $this->id);
		}
		return $this->country_language_list;
	}
	/**
	 * @return SearchEngine[]
	 */
	public function getSearchEngineList(): array
	{
		if ($this->search_engine_list === null) {
			$this->search_engine_list = SearchEngine::findAllBy(SearchEngine::_COUNTRY_ID, $this->id);
		}
		return $this->search_engine_list;
	}
	/**
	 * @return City[]
	 */
	public function getCityList(): array
	{
		if ($this->city_list === null) {
			$this->city_list = City::findAllBy(City::_COUNTRY_ID, $this->id);
		}
		return $this->city_list;
	}

	public function save(?array $data = null): static
	{
		return parent::save($data ?? [
				static::_NAME => $this->name->getValue(),
				static::_NAME_ENGLISH => $this->name_english->getValue(),
				static::_ISO_CODE_2 => $this->iso_code_2->getValue(),
				static::_ISO_CODE_3 => $this->iso_code_3->getValue(),
				static::_ISO_CODE_NUMERIC => $this->iso_code_numeric->getValue(),
				static::_FLAG_SVG_HTML => $this->flag_svg_html->getValue(),
			]);
	}


	public static function init(_String $name, _String $name_english, _String $iso_code_2, _String $flag_svg_html, ?_String $iso_code_3 = null, ?_Int $iso_code_numeric = null, ?_Int $id = null): static
	{
		$_object = new static();

		$_object->id = $id;
		$_object->name = $name;
		$_object->name_english = $name_english;
		$_object->iso_code_2 = $iso_code_2;
		$_object->flag_svg_html = $flag_svg_html;
		$_object->iso_code_3 = $iso_code_3;
		$_object->iso_code_numeric = $iso_code_numeric;

		return $_object;
	}

	public static function fromArray(array $data): static
	{
		return static::init(
			name: _string($data[static::_NAME]),
			name_english: _string($data[static::_NAME_ENGLISH]),
			iso_code_2: _string($data[static::_ISO_CODE_2]),
			flag_svg_html: _string($data[static::_FLAG_SVG_HTML]),
			iso_code_3: array_key_exists(static::_ISO_CODE_3, $data) ? _string($data[static::_ISO_CODE_3]) : null,
			iso_code_numeric: array_key_exists(static::_ISO_CODE_NUMERIC, $data) ? _int($data[static::_ISO_CODE_NUMERIC]) : null,
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
