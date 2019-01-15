<?php

namespace iBrand\Express\Numbers;

use GuzzleHttp\Client;
use iBrand\Express\Contracts\StorageInterface;
use iBrand\Express\Storage\CacheStorage;

class Kuaidi100Number extends BaseNumber
{
	protected $storage;

	protected $key;

	const AUTO_NUMBER_URL = 'http://www.kuaidi100.com/autonumber/auto?num=%s&key=%s';

	public function __construct($number, $storage = null)
	{
		parent::__construct($number);

		$this->setKey($this->number);

		if ($storage == null || !($storage instanceof StorageInterface)) {
			$cache = config('ibrand.express.storage', CacheStorage::class);;
			$this->storage = new $cache;
		}
	}

	/**
	 * @return null|string
	 */
	public function getComCode()
	{
		$comCode = $this->storage->get($this->key);
		if (!empty($comCode)) {
			return $comCode;
		}

		$url      = $this->getAutoNumberUrl($this->number);
		$client   = new Client();
		$response = $client->get($url);
		$content  = $response->getBody()->getContents();
		$result   = json_decode($content, true);
		if (!empty($result)) {
			$this->storage->set($this->key, $content);

			return $content;
		}

		return null;
	}

	public function setKey($number)
	{
		$key       = 'ibrand.express.number.' . $number;
		$this->key = md5($key);
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getStorage()
	{
		return $this->storage;
	}

	public function getAutoNumberUrl($number)
	{
		$key = config('ibrand.express.gateways.kuaidi100.key');

		return sprintf(self::AUTO_NUMBER_URL, $number, $key);
	}
}