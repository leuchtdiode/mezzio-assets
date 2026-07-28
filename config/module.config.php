<?php
namespace Assets;

use Assets\File\Type\NullProcessor;
use Assets\File\Type\Type;
use Assets\File\Type\WebpProcessor;
use Common\Router\HttpRouteCreator;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Ramsey\Uuid\Doctrine\UuidType;

return [

	'assets' => [
		'rest' => [
			'enabled' => true,
		],
		'file' => [
			'cacheTimeInSeconds' => 0, // 0 = no cache
			'processor'          => [
				Type::ORIGINAL => [
					'processor' => NullProcessor::class,
				],
				Type::WEBP     => [
					'processor' => WebpProcessor::class,
					'mimeType'  => 'image/webp',
					'extension' => 'webp',
					'options'   => [
						'method'             => 4,
						'compressionQuality' => 100,
					],
				],
			],
		],
	],

	'routes' => [
		'assets' => HttpRouteCreator::create()
			->setRoute('/assets')
			->setMayTerminate(false)
			->setChildRoutes([
				'file' => include 'routes/file.php',
			]),
	],

	'doctrine' => [
		'types'  => [
			UuidType::NAME => UuidType::class,
		],
		'driver' => [
			'orm_default' => [
				'class' => AttributeDriver::class,
				'paths' => [ __DIR__ . '/../src/Db' ],
			],
		],
	],

	'dependencies' => [
		'abstract_factories' => [
			DefaultFactory::class,
		],
	],
];