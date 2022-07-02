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


	public static function init(string $data): self
	{
		$data = json_decode($data, true);
		return new self(
			google_search_parameter_hl: $data['google_search_parameter_hl'] ?? null
		);
	}
}
