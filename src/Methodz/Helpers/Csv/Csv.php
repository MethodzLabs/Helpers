<?php

namespace Methodz\Helpers\Csv;

use Methodz\Helpers\File\File;

class Csv
{
	private array $data;
	private ?string $path;
	private ?string $fileName;

	private function __construct(string|array $data, ?string $path = null, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\")
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
			$data = explode("\n", $data);
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

	public function save(?string $path = null, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\", string $eol = "\n"): bool
	{
		if (($path === null && $this->path === null) || ($fileName === null && $this->fileName === null)) {
			throw new \Exception("Path and fileName can't be null");
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

	public static function fromFile(string $path, ?string $fileName = null, string $separator = ";", string $enclosure = '"', string $escape = "\\"): self
	{
		$content = File::get($path, $fileName);
		if ($content === false) {
			throw new \Exception("Can't load CSV file $path" . ($fileName !== null ? "/$fileName" : ""));
		}
		return new self($content, $path, $fileName, $separator, $enclosure, $escape);
	}

	public static function fromString(string $csvString): self
	{
		return new self($csvString);
	}

	public static function fromArray(array $csvData): self
	{
		return new self($csvData);
	}
}
