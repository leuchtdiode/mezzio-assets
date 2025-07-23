<?php
declare(strict_types=1);

namespace Assets\File;

class AddData
{
	private string $content;

	private string $fileName;

	private string $size;

	private string $mimeType;

	public static function create(): self
	{
		return new self();
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setContent(string $content): AddData
	{
		$this->content = $content;
		return $this;
	}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	public function setFileName(string $fileName): AddData
	{
		$this->fileName = $fileName;
		return $this;
	}

	public function getSize(): string
	{
		return $this->size;
	}

	public function setSize(string $size): AddData
	{
		$this->size = $size;
		return $this;
	}

	public function getMimeType(): string
	{
		return $this->mimeType;
	}

	public function setMimeType(string $mimeType): AddData
	{
		$this->mimeType = $mimeType;
		return $this;
	}
}