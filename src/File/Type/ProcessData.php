<?php
declare(strict_types=1);

namespace Assets\File\Type;

use Assets\File\File;

class ProcessData
{
	private File $file;

	private array $options;

	public static function create(): self
	{
		return new self();
	}

	public function getFile(): File
	{
		return $this->file;
	}

	public function setFile(File $file): ProcessData
	{
		$this->file = $file;
		return $this;
	}

	public function getOptions(): array
	{
		return $this->options;
	}

	public function setOptions(array $options): ProcessData
	{
		$this->options = $options;
		return $this;
	}
}