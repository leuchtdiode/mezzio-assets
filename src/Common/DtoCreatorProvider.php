<?php
declare(strict_types=1);

namespace Assets\Common;

use Assets\File\Creator as FileCreator;

class DtoCreatorProvider
{
	private FileCreator $fileCreator;

	public function __construct(FileCreator $fileCreator)
	{
		$this->fileCreator = $fileCreator;
	}

	public function getFileCreator(): FileCreator
	{
		return $this->fileCreator;
	}
}