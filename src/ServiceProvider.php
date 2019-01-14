<?php

namespace iBrand\Express;

use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	protected $namespace = 'iBrand\Express';

	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('ibrand/express.php'),
			]);
		}

		if (!$this->app->routesAreCached()) {
			$routeAttr = config('ibrand.express.route', []);

			Route::group(array_merge(['namespace' => $this->namespace], $routeAttr), function ($router) {
				require __DIR__ . '/route.php';
			});
		}
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'ibrand.express');
	}
}