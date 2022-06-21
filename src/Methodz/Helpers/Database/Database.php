<?php

namespace Methodz\Helpers\Database;

use Exception;
use PDO;
use PDOException;
use PDOStatement;
use Throwable;

abstract class Database
{
	private static PDO $bdd;

	const DB_SERVER = '5.135.137.111';
	const DB_NAME = 'helpers';
	const DB_USER = 'u_helpers';
	const DB_PASSWD = '3PDe7nrZN4phj6qH$';

	/**
	 * @param string     $sql    - SQL query
	 * @param array|null $params - Parameters of the request
	 *
	 * @return DatabaseQueryResult
	 */
	private static function executeRequest(string $sql, array $params = null): DatabaseQueryResult
	{
		$result = DatabaseQueryResult::init($sql, $params);
		try {
			$pdoStatement = self::getBdd()->prepare($sql);
			foreach ($params as $key => $value) {
				if (is_null($value)) {
					$pdoStatement->bindValue($key, $value, PDO::PARAM_NULL);
				} else {
					$pdoStatement->bindValue($key, $value);
				}
			}

			if ($pdoStatement->execute()) {
				$result->setResult($pdoStatement->fetchAll(PDO::FETCH_ASSOC));
			} else {
				$result->setStatus(DatabaseQueryResultStatus::ERROR);
			}
			$result
				->setPDOStatement($pdoStatement);
		} catch (Throwable $th) {
			$result
				->setError($th);
		}
		return $result;
	}

	/**
	 * Returns the results of the query as an associative array
	 *
	 * @param string      $sql
	 * @param null        $params
	 * @param string|null $keyAsIndex
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getData(string $sql, $params = null, ?string $keyAsIndex = null): DatabaseQueryResult
	{
		$data = self::executeRequest($sql, $params);

		$res = $data->getResult();

		if ($keyAsIndex !== null) {
			$tempo = $res;
			$res = [];
			foreach ($tempo as $row) {
				$res[$row[$keyAsIndex]] = $row;
			}
		}
		return $res;
	}

	/**
	 * Returns the first column of the result of a query as a flat array
	 *
	 * @param int|string $index
	 * @param string     $sql
	 * @param array|null $params
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function getColumn(int|string $index, string $sql, array $params = null): array
	{
		$data = self::executeRequest($sql, $params);
		if ($data->isOK()) {
			$column = [];

			foreach ($data->getResult() as $row) {
				$column[] = $row[$index];
			}

			return $column;
		}
		throw new Exception("Error during request (\"" . $data->getQuery() . "\", " . json_encode($data->getParameters()) . ")");
	}

	/**
	 * Returns the first line of the result of a query
	 *
	 * @param string     $sql
	 * @param array|null $params
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getRow(string $sql, array $params = null): DatabaseQueryResult
	{
		$sql .= ' LIMIT 1';

		$data = self::executeRequest($sql, $params);
		if (count($data->getResult()) === 0) {
			$data->setStatus(DatabaseQueryResultStatus::NO_DATA_FOUND);
		} else {
			$data->setResult($data->getResult()[0]);
		}

		return $data;
	}

	/**
	 * Returns the value of the first field of the first row in a query
	 *
	 * @param string     $sql
	 * @param array|null $params
	 *
	 * @return DatabaseQueryResultStatus - false: no data
	 * @throws Throwable
	 */
	public static function getValue(string $sql, array $params = null): mixed
	{
		$data = self::getRow($sql, $params);
		if ($data->isOK()) {
			$row = $data->getResult();
			return array_shift($row);
		}
		throw new Exception("Error during request (\"" . $data->getQuery() . "\", " . json_encode($data->getParameters()) . ")");
	}

	/**
	 * Returns the values of the first field of each row in a query
	 *
	 * @param string     $sql
	 * @param array|null $params
	 *
	 * @return array
	 * @throws Throwable
	 */
	public static function getValues(string $sql, array $params = null): array
	{
		$res = [];
		$data = self::getData($sql, $params);
		foreach ($data as $row) {
			$res[] = array_shift($row);
		}
		return $res;
	}

	/**
	 * @param string $table - Name of the table
	 * @param array  $data  - Associative array of data to be inserted
	 *
	 * @return DatabaseQueryResult
	 */
	public static function insert(string $table, array $data): DatabaseQueryResult
	{
		$keys = [];
		$values = [];
		$params = [];

		foreach ($data as $key => $value) {
			$keys[] = "`$key`";

			$param = ":$key";

			$values[] = $param;

			$params[$param] = $value;
		}

		$sql = 'INSERT INTO `' . $table . '` (' . implode(', ', $keys) . ')  VALUES (' . implode(', ', $values) . ')';

		return self::executeRequest($sql, $params);
	}

	/**
	 * @param string      $table        - Name of the table
	 * @param array       $data         - Associative array of with the field name as key
	 * @param string|null $where        - Condition where
	 * @param array|null  $where_params - Parameter for where
	 *
	 * @return DatabaseQueryResult
	 */
	public static function update(string $table, array $data, ?string $where = null, ?array $where_params = null): DatabaseQueryResult
	{
		$params = [];
		$fields = [];

		foreach ($data as $key => $value) {
			$fields[] = "`$key` = :$key";

			$params[":$key"] = $value;
		}

		$sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $fields);

		if ($where !== null) {
			$sql .= ' WHERE ' . $where;
		}

		if ($where_params) {
			foreach ($where_params as $key_param => $param) {
				$params[$key_param] = $param;
			}
		}

		return self::executeRequest($sql, $params);
	}

	/**
	 * Exécute une requête DELETE
	 *
	 * @param string      $table        - Name of the table
	 * @param string|null $where        - Condition where
	 * @param array|null  $where_params - Parameter for where
	 *
	 * @return DatabaseQueryResult
	 * @author ThomasFONTAINE--TUFFERY
	 */
	public static function delete(string $table, ?string $where = null, ?array $where_params = null): DatabaseQueryResult
	{
		$params = [];
		$sql = 'DELETE FROM `' . $table . '`';

		if ($where !== null) {
			$sql .= ' WHERE ' . $where;
		}

		if ($where_params) {
			foreach ($where_params as $key_param => $param) {
				$params[$key_param] = $param;
			}
		}
		return self::executeRequest($sql, $params);
	}


	/**
	 * Get id of the last element inserted in the DB
	 *
	 * @return int
	 */
	public static function getLastInsertId(): int
	{
		return self::getBdd()->lastInsertId();
	}

	/**
	 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
	 *
	 * @return PDO Objet PDO de connexion à la BDD
	 */
	private static function getBdd(): PDO
	{
		if (!isset(self::$bdd)) {
			// Création de la connexion
			try {
				self::$bdd = new PDO('mysql:host=' . self::DB_SERVER . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASSWD);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			self::$bdd->query("SET NAMES UTF8");
		}

		return self::$bdd;
	}

}
