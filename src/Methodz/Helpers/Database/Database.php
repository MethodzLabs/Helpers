<?php

namespace Methodz\Helpers\Database;

use Exception;
use Methodz\Helpers\Database\Query\Query;
use Methodz\Helpers\Database\Query\QuerySelect;
use PDO;
use Throwable;

abstract class Database
{
	const DB_SERVER = '5.135.137.111';
	const DB_NAME = 'helpers';
	const DB_USER = 'u_helpers';
	const DB_PASSWD = '3PDe7nrZN4phj6qH$';
	private static PDO $bdd;

	/**
	 * @param Query $query
	 *
	 * @return DatabaseQueryResult
	 */
	public static function executeRequest(Query $query): DatabaseQueryResult
	{
		$result = DatabaseQueryResult::init($query->getSql(), $query->getParameters());
		try {
			$pdoStatement = self::getBdd()->prepare($query->getSql());
			if ($query->getParameters() !== null) {
				foreach ($query->getParameters() as $key => $value) {
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
	 * Get id of the last element inserted in the DB
	 *
	 * @return int
	 */
	public static function getLastInsertId(): int
	{
		return self::getBdd()->lastInsertId();
	}

}
