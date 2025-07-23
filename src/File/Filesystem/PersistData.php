<?php
declare(strict_types=1);

namespace Assets\File\Filesystem;

use Assets\Db\File\Entity;

class PersistData
{
	private Entity $entity;

	private string $content;

	public static function create(): self
	{
		return new self();
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}

	public function setEntity(Entity $entity): PersistData
	{
		$this->entity = $entity;
		return $this;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setContent(string $content): PersistData
	{
		$this->content = $content;
		return $this;
	}
}