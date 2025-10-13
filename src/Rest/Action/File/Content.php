<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Assets\Common\MemoryUtil;
use Assets\File\Filesystem\PathProvider;
use Assets\File\Provider;
use Assets\File\Type\ProcessData;
use Assets\File\Type\Processor;
use Assets\Rest\Action\Base;
use DateTime;
use Exception;
use Laminas\Diactoros\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
	public function executeAction(ServerRequestInterface $request): ResponseInterface
	{
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

		$memoryLimit       = MemoryUtil::getMemoryLimitInBytes();
		$fileSize          = (int)$file->getSize();
		$targetMemoryLimit = $fileSize * 2;

		// set memory limit twice the size of the file size to avoid memory leaks
		if ($targetMemoryLimit > $memoryLimit)
		{
			ini_set('memory_limit', round($targetMemoryLimit / 1000 / 1024) . 'M');
		}

		$pathWithType = $path . '.' . $type;

		if (!file_exists($pathWithType))
		{
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
			$content = file_get_contents($pathWithType);
		}

		$outputFileName = $request->getAttribute('fileName') . '.' . $request->getAttribute('extension');

		$headers = [
			'Content-disposition' => 'inline; filename=' . $outputFileName,
			'Content-type'        => $typeConfig['mimeType'] ?? $file->getMimeType(),
			'Content-size'        => strlen($content),
		];

		if (($cacheTimeInSeconds = $this->config['assets']['file']['cacheTimeInSeconds'] ?? null))
		{
			$headers['Cache-Control'] = 'public, max-age=' . $cacheTimeInSeconds;
			$headers['ETag']          = md5($content);
			$headers['Pragma']        = '';
			$headers['Expires']       = (new DateTime())
				->modify('+' . $cacheTimeInSeconds . ' seconds')
				->format('D, d M Y H:i:s \G\M\T');
		}

		return new Response\TextResponse(
			text: $content,
			status: 200,
			headers: $headers
		);
	}
}
