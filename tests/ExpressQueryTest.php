<?php

namespace iBrand\Express\Test;

use Express;

class ExpressQueryTest extends BaseTest
{
	/** @test */
	public function testQuery()
	{
		$number = '3394542870935';

		$result = Express::query($number);

		$this->assertTrue(true);
	}
}