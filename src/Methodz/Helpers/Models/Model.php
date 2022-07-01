<?php

namespace Methodz\Helpers\Models;

use Exception;
use Methodz\Helpers\Database\Database;
use Methodz\Helpers\Type\Pair;

abstract class Model implements ModelInterface
{
	use CommonTrait;

	protected ?int $id;
	const _TABLE = "TABLE";
	const _ID = "ID";


	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param int|null $id
	 *
	 * @return static
	 */
	public function setId(?int $id): static
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
		$data = Database::getData("SELECT * FROM `" . static::_TABLE . "`");
		$result = null;
		if ($data->isOK()) {
			$result = self::arrayToObjects($data->getResult(), $idAsKey);
		}
		return $result;
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
		$data = Database::getData("SELECT * FROM `" . static::_TABLE . "` WHERE `" . static::_TABLE . "`.`" . $column . "` " . $pair->first, $pair->second);
		if ($data->isOK()) {
			return static::arrayToObjects($data->getResult());
		}
		return null;
	}


	protected static function findBy(string $column, mixed $value, bool $negation = false): ?static
	{
		$pair = self::generateFindsComparatorAndParameters($value, $negation);
		$data = Database::getRow("SELECT * FROM `" . static::_TABLE . "` WHERE `" . static::_TABLE . "`.`" . $column . "` " . $pair->first, $pair->second);
		if ($data->isOK()) {
			return static::arrayToObject($data->getResult());
		}
		return null;
	}

	public static function findById(int $id): ?static
	{
		return static::findBy(static::_ID, $id);
	}

	/**
	 * @throws Exception
	 */
	public function save(?array $data = null): static
	{
		$message = "";
		if ($this->getId() === null) {
			$result = Database::insert(
				table: static::_TABLE,
				data: $data
			);
			if ($result->isOK()) {
				$this->setId(Database::getLastInsertId());
			} else {
				$message = "Object " . static::class . " can't be inserted";
			}
		} else {
			$result = Database::update(
				table: static::_TABLE,
				data: $data,
				where: "`" . self::_ID . "`=:id",
				where_params: [
					':id' => $this->getId(),
				]
			);
			if (!$result->isOK()) {
				$message = "Object " . static::class . " can't be updated";
			}
		}

		if (!$result->isOK()) {
			throw new Exception($message);
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
			$object = static::arrayToObject($row);
			if ($idAsKey) {
				$result[$object->getId()] = $object;
			} else {
				$result[] = $object;
			}
		}
		return $result;
	}

	public function toString(): string
	{
		return static::class . " object:" . $this->id;
	}
}
