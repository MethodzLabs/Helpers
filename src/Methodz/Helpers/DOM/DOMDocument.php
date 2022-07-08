<?php

namespace Methodz\Helpers\DOM;

use DOMElement;
use DOMXPath;
use Methodz\Helpers\File\File;

class DOMDocument
{
	private \DOMDocument $dOMDocument;

	private function __construct()
	{
		$this->dOMDocument = new \DOMDocument();
	}

	/**
	 * @param string $xpath
	 *
	 * @return DOMElement[]
	 */
	public function findElementsByXPath(string $xpath): array
	{
		$domXpath = new DOMXPath($this->dOMDocument);
		return [...$domXpath->evaluate($xpath)];
	}


	/**
	 * @param string      $path
	 * @param string|null $fileName
	 * @param int|null    $options
	 *
	 * @return static
	 */
	public static function fromHTMLFile(string $path, ?string $fileName = null, int $options = 0): self
	{
		$html = File::get($path, $fileName);
		return self::fromHTML($html, $options);
	}

	/**
	 * @param string   $html
	 * @param int|null $options
	 *
	 * @return static
	 */
	public static function fromHTML(string $html, int $options = 0): self
	{
		$obj = new self();
		$obj->dOMDocument->loadHTML($html, $options);

		return $obj;
	}
}
