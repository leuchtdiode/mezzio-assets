<?php
declare(strict_types=1);

namespace Assets\File;

class RemoveData
{
	private File $file;

	public static function create(): self
	{
		return new self();
	}

	public function getFile(): File
	{
		return $this->file;
	}

	public function setFile(File $file): RemoveData
	{
		$this->file = $file;
		return $this;
	}
}