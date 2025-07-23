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
	private Provider $provider;

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * @throws Exception
	 */
	public function executeAction(ServerRequestInterface $request): ResponseInterface
	{
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
