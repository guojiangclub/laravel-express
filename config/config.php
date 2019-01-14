<?php

return [
	'route'    => [
		'prefix'     => 'express',
		'middleware' => ['web'],
	],

	// 默认快递查询配置
	'default'  => [
		'gateways' => [
			'kuaidi100',
		],
	],

	// 可用的网关配置
	'gateways' => [
		'kuaidi100' => [
			'key'      => env('kuaidi100_key', ''),
			'customer' => env('kuaidi100_customer', ''),
		],
	],

	'storage' => iBrand\Express\Storage\CacheStorage::class,
];