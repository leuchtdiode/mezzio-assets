<?php
declare(strict_types=1);

namespace Assets\Common;

class MemoryUtil
{
	public static function getMemoryLimitInBytes(): int
	{
		$s = ini_get('memory_limit') ?? 0;

		return (int)preg_replace_callback('/(\-?\d+)(.?)/', function ($m)
		{
			return $m[1] * pow(1024, strpos('BKMG', $m[2]));
		}, strtoupper($s));
	}
}
