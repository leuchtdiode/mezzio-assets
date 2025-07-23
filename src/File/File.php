<?php
declare(strict_types=1);

namespace Assets\File;

use Assets\Db\File\Entity;
use Assets\File\Url\Url;
use Common\Dto\Dto;
use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;
use DateTime;
use Ramsey\Uuid\UuidInterface;

class File implements Dto, ArrayHydratable
{
	private Entity $entity;

	/**
	 * @var Url[]
	 */
	#[ObjectToArrayHydratorProperty]
	private array $urls;

	/**
	 * @param Url[] $urls
	 */
	public function __construct(Entity $entity, array $urls)
	{
		$this->entity = $entity;
		$this->urls   = $urls;
	}

	/**
	 * @return Url[]
	 */
	public function getUrls(): array
	{
		return $this->urls;
	}

	#[ObjectToArrayHydratorProperty]
	public function getId(): UuidInterface
	{
		return $this->entity->getId();
	}

	#[ObjectToArrayHydratorProperty]
	public function getFileName(): string
	{
		return $this->entity->getFileName();
	}

	#[ObjectToArrayHydratorProperty]
	public function getSize(): string
	{
		return $this->entity->getSize();
	}

	#[ObjectToArrayHydratorProperty]
	public function getMimeType(): string
	{
		return $this->entity->getMimeType();
	}

	#[ObjectToArrayHydratorProperty]
	public function getCreatedDate(): DateTime
	{
		return $this->entity->getCreatedDate();
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}
}