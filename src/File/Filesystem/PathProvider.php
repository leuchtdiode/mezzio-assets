<?php
declare(strict_types=1);

namespace Assets\File\Filesystem;

use Assets\Db\File\Entity;
use Assets\File\Helper;

class PathProvider
{
	private array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function byEntity(Entity $entity): string
	{
		return sprintf(
			'%s/%s/%s/%s.%s',
			getcwd(),
			$this->config['assets']['storePath'],
			$entity->getCreatedDate()->format('Ym'),
			$entity->getId()->toString(),
			Helper::getExtensionByFileName(
				$entity->getFileName()
			)
		);
	}
}