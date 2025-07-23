<?php
declare(strict_types=1);

namespace Assets\File;

use Assets\Common\DtoCreatorProvider;
use Assets\Db\File\Entity;
use Assets\Db\File\Repository;

class Provider
{
	private Repository $repository;

	private DtoCreatorProvider $dtoCreatorProvider;

	public function __construct(Repository $repository, DtoCreatorProvider $dtoCreatorProvider)
	{
		$this->repository         = $repository;
		$this->dtoCreatorProvider = $dtoCreatorProvider;
	}

	public function byId(string $id): ?File
	{
		return ($entity = $this->repository->find($id))
			? $this->createDto($entity)
			: null;
	}

	private function createDto(Entity $entity): File
	{
		return $this->dtoCreatorProvider
			->getFileCreator()
			->byEntity($entity);
	}
}