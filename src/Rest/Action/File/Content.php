<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Assets\Common\MemoryUtil;
use Assets\File\Filesystem\PathProvider;
use Assets\File\Provider;
use Assets\File\Type\ProcessData;
use Assets\File\Type\Processor;
use Assets\Rest\Action\Base;
use Common\Action\ExecuteActionParams;
use DateTime;
use Exception;
use Laminas\Diactoros\Response;
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

		$pathWithType = $path . '.' . $type;

		if (!file_exists($pathWithType))
		{
			$this->raiseMemoryLimit((int)$file->getSize());

			$processResult = $processor->process(
				ProcessData::create()
					->setFile($file)
					->setOptions($typeConfig['options'] ?? [])
			);

			$content = $processResult->getContent();

			file_put_contents($pathWithType, $content);
		}
		else
		{
			$this->raiseMemoryLimit((int)filesize($pathWithType));

			$content = file_get_contents($pathWithType);
		}

		$outputFileName = $request->getAttribute('fileName') . '.' . $request->getAttribute('extension');

		$headers = [
			'Content-Disposition' => 'inline; filename=' . $outputFileName,
			'Content-Type'        => $typeConfig['mimeType'] ?? $file->getMimeType(),
			'Content-Length'      => (string)strlen($content),
		];

		if (($cacheTimeInSeconds = $this->config['assets']['file']['cacheTimeInSeconds'] ?? null))
		{
			$headers['Cache-Control'] = 'public, max-age=' . $cacheTimeInSeconds;
			$headers['ETag']          = md5($content);
			$headers['Pragma']        = '';
			$headers['Expires']       = new DateTime()
				->modify('+' . $cacheTimeInSeconds . ' seconds')
				->format('D, d M Y H:i:s \G\M\T');
		}

		return new Response\TextResponse(
			text: $content,
			status: 200,
			headers: $headers
		);
	}

	/**
	 * Raise the memory limit to twice the given size, as the content is held in memory as a whole.
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
