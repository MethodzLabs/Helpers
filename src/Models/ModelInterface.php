<?php

namespace Models;

use Database\Query\QuerySelect;

/**
 * @method init
 */
interface ModelInterface
{
	public function getId(): ?int;

	public function setId(int $id): static;

	public function save(?array $data = null): static;

	public static function findAll(): ?array;

	public static function findAllByQuery(QuerySelect $query): ?array;

	public static function findByQuery(QuerySelect $query): ?static;

	public static function arrayToObject(array $data): static;

	/**
	 * @param array $data
	 * @param bool  $idAsKey
	 *
	 * @return self[]
	 */
	public static function arrayToObjects(array $data, bool $idAsKey = false): array;
}
