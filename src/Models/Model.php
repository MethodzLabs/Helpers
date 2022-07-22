<?php

namespace Methodz\Helpers\Models;

use Exception;
use Methodz\Helpers\Database\Query\QueryHandler;
use Methodz\Helpers\Database\Query\QuerySelect;
use Methodz\Helpers\Type\_Int;
use Methodz\Helpers\Type\Pair;

abstract class Model implements ModelInterface
{
	use CommonTrait;

	const _DATABASE = "DATABASE";
	const _TABLE = "TABLE";
	const _ID = "ID";

	protected ?_Int $id;
	protected ?array $__data = null;


	public function getId(): ?_Int
	{
		return $this->id;
	}

	/**
	 * @param _Int|null $id
	 *
	 * @return static
	 */
	public function setId(?_Int $id): static
	{
		$this->id = $id;

		return $this;
	}


	/**
	 * @param mixed $value
	 * @param bool  $negation
	 *
	 * @return Pair first: comparator, second: parameters
	 */
	private static function generateFindsComparatorAndParameters(mixed $value, bool $negation): Pair
	{
		$comparator = "= :value";
		if ($negation) {
			$comparator = "<> :value";
		}
		$parameters = [':value' => $value];
		if (is_string($value)) {
			$comparator = "LIKE :value";
			if ($negation) {
				$comparator = "NOT LIKE :value";
			}
		} elseif (is_null($value)) {
			$comparator = "IS NULL";
			if ($negation) {
				$comparator = "IS NOT NULL";
			}
			$parameters = null;
		}

		return Pair::init($comparator, $parameters);
	}


	/**
	 * @return static[]|null
	 */
	public static function findAll(bool $idAsKey = false): ?array
	{
		$query = QueryHandler::select("*")
			->from("`" . static::_TABLE . "`");
		$data = static::_DATABASE::getData($query);
		$result = null;
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult(), $idAsKey);
		}
		return $result;
	}

	/**
	 * @param QuerySelect $query
	 *
	 * @return static[]|null
	 */
	public static function findAllByQuery(QuerySelect $query): ?array
	{
		$data = static::_DATABASE::getData($query);
		if ($data->isOK()) {
			return static::arrayToObjects($data->getResult());
		}
		return null;
	}

	/**
	 * @param string $column
	 * @param mixed  $value
	 * @param bool   $negation
	 *
	 * @return static[]|null
	 */
	protected static function findAllBy(string $column, mixed $value, bool $negation = false): ?array
	{
		$pair = self::generateFindsComparatorAndParameters($value, $negation);
		$query = QueryHandler::select("*")
			->from("`" . static::_TABLE . "`")
			->where("`" . static::_TABLE . "`.`" . $column . "` " . $pair->first)
			->addParameters($pair->second);
		$data = static::_DATABASE::getData($query);
		if ($data->isOK()) {
			return static::arrayToObjects($data->getResult());
		}
		return null;
	}

	/**
	 * @param QuerySelect $query
	 *
	 * @return static|null
	 */
	public static function findByQuery(QuerySelect $query): ?static
	{
		$data = static::_DATABASE::getRow($query);
		if ($data->isOK()) {
			return static::fromArray($data->getResult());
		}
		return null;
	}


	protected static function findBy(string $column, mixed $value, bool $negation = false): ?static
	{
		$pair = self::generateFindsComparatorAndParameters($value, $negation);
		$query = QueryHandler::select("*")
			->from("`" . static::_TABLE . "`")
			->where("`" . static::_TABLE . "`.`" . $column . "` " . $pair->first)
			->addParameters($pair->second);
		$data = static::_DATABASE::getRow($query);
		if ($data->isOK()) {
			return static::fromArray($data->getResult());
		}
		return null;
	}

	public static function findById(_Int $id): ?static
	{
		return static::findBy(static::_ID, $id);
	}

	/**
	 * @throws Exception
	 */
	public function save(?array $data = null): static
	{
		if ($this->getId() === null) {
			$result = static::_DATABASE::executeRequest(QueryHandler::insert(static::_TABLE)->columns(array_keys($data))->values($data));
			if ($result->isOK()) {
				$this->setId(static::_DATABASE::getLastInsertId());
			} else {
				$message = "Object " . static::class . " can't be inserted " . $this;
			}
		} else {
			$result = static::_DATABASE::executeRequest(QueryHandler::update(static::_TABLE)->set($data)->where("`" . self::_ID . "`=:id")->addParameter('id', $this->getId()));
			if (!$result->isOK()) {
				$message = "Object " . static::class . " can't be updated " . $this;
			}
		}

		if (!$result->isOK()) {
			throw new Exception($message, previous: $result->getError());
		}

		return $this;
	}

	/**
	 * @param array $data
	 * @param bool  $idAsKey
	 *
	 * @return static[]
	 */
	public static function arrayToObjects(array $data, bool $idAsKey = false): array
	{
		$result = [];
		foreach ($data as $row) {
			$object = static::fromArray($row);
			if ($idAsKey) {
				$result[$object->getId()] = $object;
			} else {
				$result[] = $object;
			}
		}
		return $result;
	}

	protected function set_data(array $data): static
	{
		$this->__data = $data;

		return $this;
	}

	protected function get_data(): array
	{
		return $this->__data;
	}
}
