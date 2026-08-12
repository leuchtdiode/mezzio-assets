<?php
namespace Assets;

use Assets\Rest\Action\File\Add;
use Assets\Rest\Action\File\Content;
use Assets\Rest\Action\File\Get;
use Common\Router\HttpRouteCreator;

return HttpRouteCreator::create()
	->setRoute('/file')
	->setMayTerminate(false)
	->setChildRoutes(
		[
			'add'         => HttpRouteCreator::create()
				->setMethods([ 'POST' ])
				->setAction(Add::class),
			'single-item' => HttpRouteCreator::create()
				->setRoute('/:fileId')
				->setConstraints([
					'fileId' => '.{36}',
				])
				->setMayTerminate(false)
				->setChildRoutes(
					[
						'get'     => HttpRouteCreator::create()
							->setMethods([ 'GET' ])
							->setAction(Get::class),
						'content' => HttpRouteCreator::create()
							->setRoute('/:type/:fileName:.:extension')
							->setConstraints(
								[
									'type'      => '\w+',
									'fileName'  => '[\w\-]+',
									'extension' => '\w+',
								]
							)
							->setAction(Content::class),
					]
				),
		]
	);
