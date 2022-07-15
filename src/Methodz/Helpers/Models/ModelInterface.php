<?php

namespace Methodz\Helpers\Models;

/**
 * @method init
 */
interface ModelInterface
{
	public function getId(): ?int;

	public function setId(int $id): static;

	public function save(?array $data = null): static;

	public static function findAll(): ?array;

	public static function findAllByQuery(): ?array;

	public static function findByQuery(): ?static;

	public static function arrayToObject(array $data): static;

	/**
	 * @param array $data
	 * @param bool  $idAsKey
	 *
	 * @return self[]
	 */
	public static function arrayToObjects(array $data, bool $idAsKey = false): array;
}
