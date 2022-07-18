<?php

namespace Methodz\Helpers\Csv;

use Exception;
use Methodz\Helpers\File\File;

class Csv
{
	private array $data;
	private ?string $path;
	private ?string $fileName;

	private function __construct(string|array $data, ?string $path = null, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n")
	{
		$this->path = $path;
		$this->fileName = $fileName;
		$this->data = [];
		if (is_array($data)) {
			$keys = [];
			foreach ($data as $row) {
				foreach ($row as $key => $value) {
					if (!in_array($key, $keys)) {
						$keys[] = $key;
					}
				}
			}
			$i = 1;
			foreach ($data as $row) {
				$this->data[$i] = [];
				foreach ($keys as $key) {
					$this->data[$i][$key] = "";
					if (array_key_exists($key, $row)) {
						$this->data[$i][$key] = (string)$row[$key];
					}
				}
				$i++;
			}
		} else {
			$data = explode($eol, $data);
			$rowsToDelete = [];
			foreach ($data as $rowNumber => &$row) {
				if (empty($row)) {
					$rowsToDelete[] = $rowNumber;
				}
				$row = str_getcsv($row, $separator, $enclosure, $escape);
			}
			foreach ($rowsToDelete as $rowNumber) {
				unset($data[$rowNumber]);
			}
			if (count($data) > 0) {
				$header = $data[0];
				unset($data[0]);
				$i = 1;
				foreach ($data as $row) {
					$this->data[$i] = [];
					foreach ($row as $key => $value) {
						$this->data[$i][$header[$key]] = $value;
					}
					$i++;
				}
			}
		}
	}

	/**
	 * Returns a 2 dimensional table for each row of the csv except the header with the column name in key
	 *
	 * @return array[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 *
	 * @return Csv
	 */
	public function setData(array $data): self
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Save Csv as file
	 *
	 * @param string|null $path
	 * @param string|null $fileName
	 * @param string      $separator
	 * @param string      $enclosure
	 * @param string      $escape
	 * @param string      $eol
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function save(?string $path = null, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): bool
	{
		if (($path === null && $this->path === null) || ($fileName === null && $this->fileName === null)) {
			throw new Exception("Path and fileName can't be null");
		}
		if ($path === null) {
			$path = $this->path;
		}
		if ($fileName === null) {
			$fileName = $this->fileName;
		}

		$data = $this->getData();
		if (count($data) > 0) {
			array_unshift($data, array_keys($data[array_keys($data)[0]]));
		}

		$fp = fopen($path . ($fileName !== null ? "/$fileName" : ""), 'w');

		foreach ($data as $fields) {
			fputcsv($fp, $fields, separator: $separator, enclosure: $enclosure, escape: $escape, eol: $eol);
		}

		fclose($fp);

		return true;
	}

	/**
	 * Create a Csv from a Csv file
	 *
	 * @param string      $path
	 * @param string|null $fileName
	 * @param string      $separator
	 * @param string      $enclosure
	 * @param string      $escape
	 * @param string      $eol
	 *
	 * @return static
	 * @throws Exception
	 */
	public static function fromFile(string $path, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): self
	{
		$content = File::get($path, $fileName);
		if ($content === false) {
			throw new Exception("Can't load Csv file $path" . ($fileName !== null ? "/$fileName" : ""));
		}
		return new self($content, $path, $fileName, $separator, $enclosure, $escape, $eol);
	}

	/**
	 * Create a Csv from a string in Csv format
	 *
	 * @param string $csvString
	 * @param string $separator
	 * @param string $enclosure
	 * @param string $escape
	 * @param string $eol
	 *
	 * @return static
	 */
	public static function fromString(string $csvString, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): self
	{
		return new self($csvString, separator: $separator, enclosure: $enclosure, escape: $escape, eol: $eol);
	}

	/**
	 * Create a Csv from a table
	 *
	 * @param array $csvData
	 *
	 * @return static
	 */
	public static function fromArray(array $csvData): self
	{
		return new self($csvData);
	}
}
