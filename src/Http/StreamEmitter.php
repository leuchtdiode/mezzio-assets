<?php
declare(strict_types=1);

namespace Assets\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Psr\Http\Message\ResponseInterface;

/**
 * Emits responses whose body is a file on disk in chunks, all others are passed on to the next emitter
 */
class StreamEmitter implements EmitterInterface
{
	private readonly SapiStreamEmitter $emitter;

	public function __construct()
	{
		$this->emitter = new SapiStreamEmitter();
	}

	public function emit(ResponseInterface $response): bool
	{
		if ($response->getBody()->getMetadata('wrapper_type') !== 'plainfile')
		{
			return false;
		}

		return $this->emitter->emit($response);
	}
}
