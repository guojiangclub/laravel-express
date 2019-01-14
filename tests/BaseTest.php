<?php

namespace iBrand\Express\Test;

use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{


	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			\Orchestra\Database\ConsoleServiceProvider::class,
			\iBrand\Express\ServiceProvider::class,
		];
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageAliases($app)
	{
		return [
			'Express' => "iBrand\Express\Facade",
		];
	}
}