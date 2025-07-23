<?php
declare(strict_types=1);

namespace Assets\File;

use Assets\Common\EntityDtoCreator;
use Assets\Db\File\Entity;
use Assets\File\Url\Provider as UrlProvider;
use Common\Dto\Dto;

readonly class Creator implements EntityDtoCreator
{
	public function __construct(
		private UrlProvider $urlsProvider
	)
	{
	}

	/**
	 * @param Entity $entity
	 * @return File
	 */
	public function byEntity($entity, ?CreateOptions $createOptions = null): Dto
	{
		return new File(
			$entity,
			$this->urlsProvider->get($entity, $createOptions?->getTypes())
		);
	}
}