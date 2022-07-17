<?php

namespace Models;

enum SearchEngineTypeEnum: string
{
	use CommonEnumTrait;

	case GOOGLE_SEARCH = "Google Search";
	case BING_SEARCH = "Bing Search";
	case YAHOO_SEARCH = "Yahoo Search";

	public function toString(): string
	{
		return $this->value;
	}
}
