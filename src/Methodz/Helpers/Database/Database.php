<?php

namespace Methodz\Helpers\Database;

use Exception;
use Methodz\Helpers\Database\Query\QuerySelect;
use PDO;
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
	public static function executeRequest(string $sql, ?array $params = null): DatabaseQueryResult
	{
		$result = DatabaseQueryResult::init($sql, $params);
		try {
			$pdoStatement = self::getBdd()->prepare($sql);
			if ($params !== null) {
				foreach ($params as $key => $value) {
					if (is_null($value)) {
						$pdoStatement->bindValue($key, $value, PDO::PARAM_NULL);
					} else {
						$pdoStatement->bindValue($key, $value);
					}
				}
			}

			if ($pdoStatement->execute()) {
				$result
					->setStatus(DatabaseQueryResultStatus::OK)
					->setResult($pdoStatement->fetchAll(PDO::FETCH_ASSOC));
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
	 * @param QuerySelect $query
	 * @param string|null $keyAsIndex
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getData(QuerySelect $query, ?string $keyAsIndex = null): DatabaseQueryResult
	{
		$data = $query->execute();

		if ($keyAsIndex !== null) {
			$res = $data->getResult();
			$tempo = $res;
			$res = [];
			foreach ($tempo as $row) {
				$res[$row[$keyAsIndex]] = $row;
			}
			$data->setResult($res);
		}
		return $data;
	}

	/**
	 * Returns the first column of the result of a query as a flat array
	 *
	 * @param int|string  $index
	 * @param QuerySelect $query
	 *
	 * @return DatabaseQueryResult
	 * @throws Exception
	 */
	public static function getColumn(int|string $index, QuerySelect $query): DatabaseQueryResult
	{
		$data = $query->execute();
		if ($data->isOK()) {
			$column = [];

			foreach ($data->getResult() as $row) {
				$column[] = $row[$index];
			}

			$data->setResult($column);
			return $data;
		}
		throw new Exception("Error during request (\"" . $data->getQuery() . "\", " . json_encode($data->getParameters()) . ")");
	}

	/**
	 * Returns the first line of the result of a query
	 *
	 * @param QuerySelect $query
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getRow(QuerySelect $query): DatabaseQueryResult
	{
		$data = $query->limit(1)->execute();
		if ($data->isOK()) {
			if (count($data->getResult()) === 0) {
				$data->setStatus(DatabaseQueryResultStatus::NO_DATA_FOUND);
			} else {
				$data->setResult($data->getResult()[0]);
			}
		}

		return $data;
	}

	/**
	 * Returns the value of the first field of the first row in a query
	 *
	 * @param QuerySelect $query
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getValue(QuerySelect $query): DatabaseQueryResult
	{
		$data = self::getRow($query);
		if ($data->isOK()) {
			$row = $data->getResult();
			$data->setResult(array_shift($row));
		}
		return $data;
	}

	/**
	 * Returns the values of the first field of each row in a query
	 *
	 * @param QuerySelect $query
	 *
	 * @return DatabaseQueryResult
	 */
	public static function getValues(QuerySelect $query): DatabaseQueryResult
	{
		$res = [];
		$data = self::getData($query);
		foreach ($data as $row) {
			$res[] = array_shift($row);
		}
		$data->setResult($res);
		return $data;
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

		if ($where_params !== null) {
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
	 * Returns a connection object to the DB by initializing the connection as needed
	 *
	 * @return PDO Objet PDO de connexion à la BDD
	 */
	private static function getBdd(): PDO
	{
		if (!isset(self::$bdd)) {
			self::$bdd = new PDO('mysql:host=' . self::DB_SERVER . ';dbname=' . self::DB_NAME, self::DB_USER, self::DB_PASSWD);
			self::$bdd->query("SET NAMES UTF8");
		}

		return self::$bdd;
	}

}
