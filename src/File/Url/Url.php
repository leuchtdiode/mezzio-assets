<?php
declare(strict_types=1);

namespace Assets\File\Url;

use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;

class Url implements ArrayHydratable
{
	#[ObjectToArrayHydratorProperty]
	private string $url;

	#[ObjectToArrayHydratorProperty]
	private string $type;

	public function __construct(string $url, string $type)
	{
		$this->url  = $url;
		$this->type = $type;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getType(): string
	{
		return $this->type;
	}
}