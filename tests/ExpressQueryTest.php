<?php

namespace iBrand\Express\Test;

use Express;
use iBrand\Express\Contracts\StorageInterface;
use iBrand\Express\Storage\CacheStorage;

class ExpressQueryTest extends BaseTest
{
	/** @test */
	public function testKey()
	{
		$number = '810597623758';
		Express::setKey($number);
		$key = md5('ibrand.express.query' . $number);
		$this->assertSame($key, Express::getKey());
	}

	/** @test */
	public function testStorage()
	{
		$cache   = config('ibrand.express.storage', CacheStorage::class);
		$storage = new $cache;
		$this->assertInstanceOf(StorageInterface::class, $storage);
		Express::setStorage($storage);
		$this->assertInstanceOf(StorageInterface::class, Express::getStorage());
	}

	/** @test */
	public function testQuery()
	{
		$default = config('ibrand.express.default.gateways', []);

		$number = '810597623758';

		$result = Express::query($number);
		$this->assertArrayHasKey($default[0], $result);

		$storage = Express::getStorage();
		$storage->forget(Express::getKey());

		$result = Express::query($number);

		$this->assertArrayHasKey($default[0], $result);
	}

	/** @test */
	public function testException()
	{
		$number = '81059762375812312';
		$result = Express::query($number, ['xxx']);

		$this->assertFalse($result);
	}

	/** @test */
	public function testController()
	{
		$number = '810597623758';

		$response = $this->get(config('ibrand.express.route.prefix') . '/query?no=' . $number);
		$this->assertJson($response->getContent());
	}
}