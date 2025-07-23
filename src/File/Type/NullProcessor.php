<?php
declare(strict_types=1);

namespace Assets\File\Type;

use Assets\File\Filesystem\PathProvider;

readonly class NullProcessor implements Processor
{
	public function __construct(
		private PathProvider $pathProvider
	)
	{
	}

	public function process(ProcessData $data): ProcessResult
	{
		$result = new ProcessResult();

		$result->setContent(
			file_get_contents(
				$this->pathProvider->byEntity(
					$data
						->getFile()
						->getEntity()
				)
			)
		);

		return $result;
	}
}