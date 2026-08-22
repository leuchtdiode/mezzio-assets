<?php
declare(strict_types=1);

namespace Assets\File;

use Assets\Db\File\Deleter;
use Assets\File\Filesystem\PathProvider;
use Exception;

class Remover
{
	private Deleter $entityDeleter;

	private PathProvider $pathProvider;

	public function __construct(Deleter $entityDeleter, PathProvider $pathProvider)
	{
		$this->entityDeleter = $entityDeleter;
		$this->pathProvider  = $pathProvider;
	}

	/**
	 * @throws Exception
	 */
	public function remove(RemoveData $data): RemoveResult
	{
		$result = new RemoveResult();
		$result->setSuccess(false);

		$file = $data->getFile();

		$entity = $file->getEntity();

		// resolve the path while the entity is still readable, deleting it may detach it
		$path = $this->pathProvider->byEntity($entity);

		// the row goes first on purpose: the foreign keys pointing at assets_files are ON DELETE RESTRICT,
		// so a file that is still referenced makes this throw and the bytes stay where they are. Unlinking
		// first would destroy the file and leave the row behind pointing at nothing.
		$this->entityDeleter->delete($entity);

		if (file_exists($path))
		{
			unlink($path);
		}

		// delete all variants as well
		foreach (glob($path . '.*') as $variantPath)
		{
			unlink($variantPath);
		}

		$result->setSuccess(true);

		return $result;
	}
}
