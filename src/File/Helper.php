<?php
declare(strict_types=1);

namespace Assets\File;

class Helper
{
	public static function getExtensionByFileName(string $fileName): string
	{
		$boom = explode('.', $fileName);

		return array_pop($boom);
	}
}