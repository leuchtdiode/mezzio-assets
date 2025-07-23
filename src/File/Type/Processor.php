<?php
declare(strict_types=1);

namespace Assets\File\Type;

interface Processor
{
	public function process(ProcessData $data): ProcessResult;
}