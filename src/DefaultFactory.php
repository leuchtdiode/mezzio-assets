<?php
declare(strict_types=1);

namespace Assets;

use Common\AbstractDefaultFactory;

class DefaultFactory extends AbstractDefaultFactory
{
	protected function getNamespace(): string
	{
		return __NAMESPACE__;
	}
}