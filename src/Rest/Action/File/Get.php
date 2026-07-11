<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Assets\File\Provider;
use Assets\Rest\Action\Base;
use Assets\Rest\Action\Response;
use Common\Hydration\ObjectToArrayHydrator;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Get extends Base
{
	public function __construct(
		private readonly array $config,
		private readonly Provider $provider
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function executeAction(ServerRequestInterface $request): ResponseInterface
	{
		if (!$this->isEnabled($this->config))
		{
			return $this->notFound();
		}

		$file = $this->provider->byId(
			$request->getAttribute('fileId')
		);

		if (!$file)
		{
			return Response::is()
				->unsuccessful()
				->dispatch();
		}

		return Response::is()
			->successful()
			->data(
				ObjectToArrayHydrator::hydrate(
					$file
				)
			)
			->dispatch();
	}
}
