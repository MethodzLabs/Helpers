<?php

namespace DOM;

use PHPUnit\Framework\TestCase;

class DOMDocumentTest extends TestCase
{

	public function testFromHTMLFile()
	{
		$domDocument = DOMDocument::fromHTMLFile(__DIR__."/../data", "test.html");
		//print_r($domDocument->findElementsByXPath("//div")[0]->parentNode);
		self::assertTrue(true);
	}
}
