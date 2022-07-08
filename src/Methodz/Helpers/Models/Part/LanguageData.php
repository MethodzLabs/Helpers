<?php

namespace Methodz\Helpers\Models\Part;

use Methodz\Helpers\Models\CommonTrait;

class LanguageData
{
	use CommonTrait;

	public ?string $google_search_parameter_hl;

	public function __construct(string $google_search_parameter_hl)
	{
		$this->google_search_parameter_hl = $google_search_parameter_hl;
	}


	public static function fromJson(string $data): self
	{
		$data = json_decode($data, true);
		return self::init(
			google_search_parameter_hl: $data['google_search_parameter_hl'] ?? null
		);
	}

	public static function init(string $google_search_parameter_hl): self
	{
		return new self(
			google_search_parameter_hl: $google_search_parameter_hl
		);
	}
}
