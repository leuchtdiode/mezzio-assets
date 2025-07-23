<?php
declare(strict_types=1);

namespace Assets\File\Type;

use Assets\File\Filesystem\PathProvider;
use Exception;
use Imagick;

readonly class WebpProcessor implements Processor
{
	public function __construct(
		private PathProvider $pathProvider
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function process(ProcessData $data): ProcessResult
	{
		$result = new ProcessResult();

		if (!extension_loaded('imagick'))
		{
			throw new Exception('Imagick is mandatory');
		}

		$options = $data->getOptions();

		$file = $data->getFile();

		$path = $this->pathProvider->byEntity($file->getEntity());

		$im = new Imagick();
		$im->pingImage($path);
		$im->readImage($path);
		$im->setImageFormat('webp');
		$im->setOption('webp:method', $options['method']);
		$im->setImageCompressionQuality($options['compressionQuality']);

		$result->setContent(
			$im->getImageBlob()
		);

		return $result;
	}
}