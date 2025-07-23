<?php
declare(strict_types=1);

namespace Assets\File;

use Assets\Common\ResultTrait;

class AddResult
{
	use ResultTrait;

	private ?File $file = null;

	public function getFile(): ?File
	{
		return $this->file;
	}

	public function setFile(?File $file): void
	{
		$this->file = $file;
	}
}