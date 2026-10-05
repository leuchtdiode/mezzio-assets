<?php
declare(strict_types=1);

namespace Assets\File\Type;

class ProcessResult
{
	private ?string $content = null;

	/**
	 * File on disk, used instead of content to avoid loading big files into memory
	 */
	private ?string $path = null;

	public function getContent(): string
	{
		return $this->content ?? file_get_contents($this->path);
	}

	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	public function getPath(): ?string
	{
		return $this->path;
	}

	public function setPath(?string $path): void
	{
		$this->path = $path;
	}
}
