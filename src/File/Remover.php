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

		$path = $this->pathProvider->byEntity($entity);

		if (file_exists($path))
		{
			unlink($path);
		}

		// delete all variants as well
		foreach (glob($path . '.*') as $variantPath)
		{
			unlink($variantPath);
		}

		$this->entityDeleter->delete($entity);

		$result->setSuccess(true);

		return $result;
	}
}