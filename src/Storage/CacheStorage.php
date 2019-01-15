<?php

namespace iBrand\Express\Storage;

use Illuminate\Support\Facades\Cache;
use iBrand\Express\Contracts\StorageInterface;

class CacheStorage implements StorageInterface
{
	/**
	 * @param $key
	 * @param $value
	 */
	public function set($key, $value)
	{
		Cache::put($key, $value, config('ibrand.express.lifetime', 180));
	}

	/**
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return Cache::get($key, $default);
	}

	/**
	 * @param $key
	 */
	public function forget($key)
	{
		if (Cache::has($key)) {
			Cache::forget($key);
		}
	}
}
