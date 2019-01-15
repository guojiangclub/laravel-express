<?php

namespace iBrand\Express\Test;

use iBrand\Express\Contracts\StorageInterface;
use iBrand\Express\Numbers\Kuaidi100Number;

class ExpressNumberTest extends BaseTest
{
	/** @test */
	public function testGetComCode()
	{
		$number        = '810597623758';
		$expressNumber = new Kuaidi100Number($number);

		$comCode = $expressNumber->getComCode();
		$this->assertJson($comCode);

		$storage = $expressNumber->getStorage();
		$this->assertInstanceOf(StorageInterface::class, $storage);
		$storage->forget($expressNumber->getKey());

		$comCode = $expressNumber->getComCode();
		$this->assertJson($comCode);

		$number        = '906919164534';
		$expressNumber = new Kuaidi100Number($number);

		$comCode = $expressNumber->getComCode();
		$this->assertSame($comCode, null);
	}
}