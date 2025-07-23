<?php
declare(strict_types=1);

namespace Assets\File\Type;

class ProcessResult
{
	private string $content;

	public function getContent(): string
	{
		return $this->content;
	}

	public function setContent(string $content): void
	{
		$this->content = $content;
	}
}