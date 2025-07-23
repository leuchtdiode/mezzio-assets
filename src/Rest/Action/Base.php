<?php
declare(strict_types=1);

namespace Assets\Rest\Action;

use Laminas\Diactoros\Response as DiactorosResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 */
abstract class Base implements RequestHandlerInterface
{
	abstract public function executeAction(ServerRequestInterface $request): ResponseInterface;

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		return $this->executeAction($request);
	}

	protected function notFound(): ResponseInterface
	{
		return new DiactorosResponse(null, 404);
	}

	protected function forbidden(): ResponseInterface
	{
		return new DiactorosResponse(null, 403);
	}
}
