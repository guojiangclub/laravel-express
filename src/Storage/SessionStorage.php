<?php

namespace iBrand\Express\Storage;

use iBrand\Express\Contracts\StorageInterface;

class SessionStorage implements StorageInterface
{
	/**
	 * @param $key
	 * @param $value
	 */
	public function set($key, $value)
	{
		session([
			$key => $value,
		]);
	}

	/**
	 * @param      $key
	 * @param null $default
	 *
	 * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
	 */
	public function get($key, $default = null)
	{
		return session($key, $default);
	}

	/**
	 * @param $key
	 */
	public function forget($key)
	{
		session()->forget($key);
	}
}
