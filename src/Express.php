<?php

namespace iBrand\Express;

use iBrand\Express\Contracts\ExpressNumberInterface;
use iBrand\Express\Contracts\GatewayInterface;
use iBrand\Express\Contracts\StorageInterface;
use iBrand\Express\Storage\CacheStorage;

class Express
{
	const STATUS_SUCCESS = 'success';

	const STATUS_FAILURE = 'failure';

	protected $storage;

	protected $key;

	protected $gateways = [];

	protected $expressNumber = [];

	public function __construct($storage = null)
	{
		if ($storage == null || !($storage instanceof StorageInterface)) {
			$cache = config('ibrand.express.storage', CacheStorage::class);;
			$this->storage = new $cache;
		}
	}

	public function query($number, array $gateways = [])
	{
		$this->setKey($number);

		$message = $this->storage->get($this->key);
		if ($message) {
			return $message;
		}

		if (empty($gateways)) {
			$gateways = config('ibrand.express.default.gateways');
		}

		$results      = [];
		$isSuccessful = false;
		$gateways     = $this->formatGateways($gateways);

		foreach ($gateways as $gateway => $config) {
			try {
				$express = $this->getExpressNumber($number, $gateway);
				$result  = $this->gateway($gateway)->query($express, $config);

				$results[$gateway] = [
					'gateway' => $gateway,
					'status'  => self::STATUS_SUCCESS,
					'result'  => $result,
				];
				$isSuccessful      = true;

				break;
			} catch (\Exception $e) {
				$results[$gateway] = [
					'gateway'   => $gateway,
					'status'    => self::STATUS_FAILURE,
					'exception' => $e->getMessage(),
				];
			}
		}

		if (!$isSuccessful) {
			return false;
		}

		$this->storage->set($this->key, json_encode($results));

		return json_encode($results);
	}

	/**
	 * @param $key
	 */
	public function setKey($key)
	{
		$key       = 'ibrand.express.query' . $key;
		$this->key = md5($key);
	}

	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @return mixed
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * @param \iBrand\Express\Contracts\StorageInterface $storage
	 */
	public function setStorage(StorageInterface $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * @param      $number
	 * @param null $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function getExpressNumber($number, $name)
	{
		if (!isset($this->expressNumber[$name])) {
			$this->expressNumber[$name] = $this->createHandle($name, $number, 'Number');
		}

		return $this->expressNumber[$name];
	}

	/**
	 * @param null $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function gateway($name)
	{
		if (!isset($this->gateways[$name])) {
			$this->gateways[$name] = $this->createHandle($name);
		}

		return $this->gateways[$name];
	}

	/**
	 * @param        $name
	 * @param string $params
	 * @param string $type
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function createHandle($name, $params = '', $type = 'Gateway')
	{
		$className = $this->formatClassName($name, $type);
		$handle    = $type == 'Gateway' ? $this->makeHandle($className, config("ibrand.express.gateways.{$name}", []), $type) : $this->makeHandle($className, $params, $type);

		if ($type == 'Gateway' && !($handle instanceof GatewayInterface)) {
			throw new \Exception(sprintf($type . ' "%s" not inherited from %s.', $name, GatewayInterface::class));
		}

		if ($type == 'Number' && !($handle instanceof ExpressNumberInterface)) {
			throw new \Exception(sprintf($type . ' "%s" not inherited from %s.', $name, ExpressNumberInterface::class));
		}

		return $handle;
	}

	/**
	 * @param        $class
	 * @param        $param
	 * @param string $type
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function makeHandle($class, $param, $type = 'Gateway')
	{
		if (!class_exists($class)) {
			throw new \Exception(sprintf($type . ' "%s" not exists.', $class));
		}

		return new $class($param);
	}

	protected function formatGateways(array $gateways)
	{
		$formatted = [];

		foreach ($gateways as $gateway => $setting) {
			if (is_int($gateway) && is_string($setting)) {
				$gateway = $setting;
				$setting = [];
			}

			$formatted[$gateway] = $setting;
			$globalSettings      = config("ibrand.express.gateways.{$gateway}", []);

			if (is_string($gateway) && !empty($globalSettings) && is_array($setting)) {
				$formatted[$gateway] = array_merge($globalSettings, $setting);
			}
		}

		return $formatted;
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	protected function formatClassName($name, $type = 'Gateway')
	{
		if (class_exists($name)) {
			return $name;
		}

		$name = ucfirst(str_replace(['-', '_', ''], '', $name));

		return __NAMESPACE__ . "\\{$type}s\\{$name}{$type}";
	}
}