<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Assets\Common\StringUtil;
use Assets\File\AddData as FileAddData;
use Assets\File\Adder;
use Assets\Rest\Action\Base;
use Assets\Rest\Action\Response;
use Common\Action\ExecuteActionParams;
use Common\Hydration\ObjectToArrayHydrator;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Add extends Base
{
	public function __construct(
		private readonly array $config,
		private readonly AddData $data,
		private readonly Adder $adder
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function executeAction(ExecuteActionParams $params): ResponseInterface
	{
		if (!$this->isEnabled($this->config))
		{
			return $this->notFound();
		}

		$request = $params->getRequest();

		$values = $this->data
			->setRequest($request)
			->getValues();

		if ($values->hasErrors())
		{
			return Response::is()
				->unsuccessful()
				->errors($values->getErrors())
				->dispatch();
		}

		$content = $values
			->get(AddData::CONTENT)
			->getValue();

		if (StringUtil::isBase64($content))
		{
			$content = base64_decode($content);
		}

		$result = $this->adder->add(
			FileAddData::create()
				->setContent($content)
				->setFileName(
					$values
						->get(AddData::FILE_NAME)
						->getValue()
				)
				->setMimeType(
					$values
						->get(AddData::MIME_TYPE)
						->getValue()
				)
				->setSize(
					(string)$values
						->get(AddData::SIZE)
						->getValue()
				)
		);

		if (!$result->isSuccess())
		{
			return Response::is()
				->unsuccessful()
				->errors($result->getErrors())
				->dispatch();
		}

		return Response::is()
			->successful()
			->data(
				ObjectToArrayHydrator::hydrate(
					$result->getFile()
				)
			)
			->dispatch();
	}
}
