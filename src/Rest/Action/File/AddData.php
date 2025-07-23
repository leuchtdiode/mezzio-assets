<?php
declare(strict_types=1);

namespace Assets\Rest\Action\File;

use Common\RequestData\Data;
use Common\RequestData\PropertyDefinition\PropertyDefinition;
use Common\RequestData\PropertyDefinition\Text;

class AddData extends Data
{
	public const string CONTENT   = 'content';
	public const string FILE_NAME = 'fileName';
	public const string MIME_TYPE = 'mimeType';
	public const string SIZE      = 'size';

	/**
	 * @return PropertyDefinition[]
	 */
	protected function getDefinitions(): array
	{
		return [
			Text::create()
				->setName(self::CONTENT)
				->setRequired(true),
			Text::create()
				->setName(self::FILE_NAME)
				->setRequired(true),
			Text::create()
				->setName(self::MIME_TYPE)
				->setRequired(true),
			Text::create()
				->setName(self::SIZE)
				->setRequired(true),
		];
	}
}