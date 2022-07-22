<?php

namespace Methodz\Models\Helpers\Enum;

enum SearchEngineTypeEnum: string
{
	use \Methodz\Helpers\Models\CommonEnumTrait;

	case GOOGLE_SEARCH = "Google Search";
	case BING_SEARCH = "Bing Search";
	case YAHOO_SEARCH = "Yahoo Search";

	public function toString(): string
	{
		return $this->value;
	}
}
