<?php
declare(strict_types=1);

namespace Assets\Rest\Action;

use Common\Action\BaseAction;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;

/**
 */
abstract class Base extends BaseAction
{
	protected function isEnabled(array $config): bool
	{
		return $config['assets']['rest']['enabled'];
	}

	protected function notFound(): ResponseInterface
	{
		return new EmptyResponse(404);
	}

	protected function forbidden(): ResponseInterface
	{
		return new EmptyResponse(403);
	}
}
