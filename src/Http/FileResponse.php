<?php
declare(strict_types=1);

namespace Assets\Http;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

/**
 * Response which streams a file from disk instead of loading it into memory
 * (emitted chunk-wise by StreamEmitter)
 */
class FileResponse extends Response
{
	public function __construct(
		string $path,
		string $fileName,
		string $contentType,
		string $disposition = 'inline',
		int $status = 200
	)
	{
		parent::__construct(
			new Stream($path, 'rb'),
			$status,
			[
				'content-type'        => $contentType,
				'content-disposition' => $disposition . ';filename=' . $fileName,
				'content-length'      => (string)filesize($path),
			]
		);
	}
}
