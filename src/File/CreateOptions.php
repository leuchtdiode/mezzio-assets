<?php
declare(strict_types=1);

namespace Assets\File;

class CreateOptions
{
	/**
	 * @var string[]
	 */
	private array $types;

	public static function create(): self
	{
		return new self();
	}

	public function getTypes(): array
	{
		return $this->types;
	}

	public function setTypes(array $types): CreateOptions
	{
		$this->types = $types;
		return $this;
	}
}