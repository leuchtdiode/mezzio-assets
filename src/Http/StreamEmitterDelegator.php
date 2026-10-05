<?php
declare(strict_types=1);

namespace Assets\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\EmitterStack;
use Psr\Container\ContainerInterface;

/**
 * Puts the StreamEmitter on top of the configured emitter, so file responses are streamed
 * while all other responses are still emitted by the application's emitter
 */
class StreamEmitterDelegator
{
	public function __invoke(ContainerInterface $container, string $name, callable $callback): EmitterInterface
	{
		$emitter = $callback();

		if (!$emitter instanceof EmitterStack)
		{
			$stack = new EmitterStack();
			$stack->push($emitter);

			$emitter = $stack;
		}

		$emitter->push(new StreamEmitter()); // LIFO, so it is asked first

		return $emitter;
	}
}
