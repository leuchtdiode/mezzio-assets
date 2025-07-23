<?php
declare(strict_types=1);

namespace Assets\Common;

class StringUtil
{
	public static function isBase64(string $content): bool
	{
		return base64_decode($content, true) !== false;
	}
}
