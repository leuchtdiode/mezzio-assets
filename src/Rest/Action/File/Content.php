<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Assets\Common\MemoryUtil;
use Assets\File\Filesystem\PathProvider;
use Assets\File\Provider;
use Assets\File\Type\ProcessData;
use Assets\File\Type\Processor;
use Assets\Http\FileResponse;
use Assets\Rest\Action\Base;
use Common\Action\ExecuteActionParams;
use DateTime;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class Content extends Base
{
	public function __construct(
		private readonly array $config,
		private readonly ContainerInterface $container,
		private readonly Provider $fileProvider,
		private readonly PathProvider $pathProvider
	)
	{
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws Exception
	 */
	public function executeAction(ExecuteActionParams $params): ResponseInterface
	{
		if (!$this->isEnabled($this->config))
		{
			return $this->notFound();
		}

		$request = $params->getRequest();

		$file = $this->fileProvider->byId(
			$request->getAttribute('fileId')
		);

		if (!$file)
		{
			return $this->notFound();
		}

		$type = $request->getAttribute('type');

		$path = $this->pathProvider->byEntity(
			$file->getEntity()
		);

		if (!file_exists($path))
		{
			return $this->notFound();
		}

		if (!($typeConfig = $this->config['assets']['file']['processor'][$type] ?? null))
		{
			throw new Exception('Could not find processor config for type "' . $type . '"');
		}

		$processor = $this->container->get($typeConfig['processor']);

		if (!$processor instanceof Processor)
		{
			throw new Exception('Invalid processor given');
		}

		$servePath = $path . '.' . $type;

		if (!file_exists($servePath))
		{
			$this->raiseMemoryLimit((int)$file->getSize());

			$processResult = $processor->process(
				ProcessData::create()
					->setFile($file)
					->setOptions($typeConfig['options'] ?? [])
			);

			if (($processedPath = $processResult->getPath()))
			{
				// processor did not create new content (e.g. original file), so no copy is needed
				$servePath = $processedPath;
			}
			else
			{
				file_put_contents($servePath, $processResult->getContent());
			}
		}

		$response = new FileResponse(
			$servePath,
			$request->getAttribute('fileName') . '.' . $request->getAttribute('extension'),
			$typeConfig['mimeType'] ?? $file->getMimeType()
		);

		if (($cacheTimeInSeconds = $this->config['assets']['file']['cacheTimeInSeconds'] ?? null))
		{
			$response = $response
				->withHeader('Cache-Control', 'public, max-age=' . $cacheTimeInSeconds)
				->withHeader('ETag', md5($servePath . filemtime($servePath) . filesize($servePath)))
				->withHeader('Pragma', '')
				->withHeader(
					'Expires',
					new DateTime()
						->modify('+' . $cacheTimeInSeconds . ' seconds')
						->format('D, d M Y H:i:s \G\M\T')
				);
		}

		return $response;
	}

	/**
	 * Raise the memory limit to twice the given size, as processed content is held in memory as a whole.
	 */
	private function raiseMemoryLimit(int $sizeInBytes): void
	{
		$memoryLimit = MemoryUtil::getMemoryLimitInBytes();

		// already unlimited, nothing to raise
		if ($memoryLimit < 0)
		{
			return;
		}

		$targetMemoryLimit = $sizeInBytes * 2;

		if ($targetMemoryLimit <= $memoryLimit)
		{
			return;
		}

		ini_set('memory_limit', (int)ceil($targetMemoryLimit / 1024 / 1024) . 'M');
	}
}
