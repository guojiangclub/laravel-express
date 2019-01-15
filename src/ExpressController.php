<?php

namespace iBrand\Express;

use Illuminate\Routing\Controller;
use Express;

class ExpressController extends Controller
{
	public function query()
	{
		$number = request('no');

		return Express::query($number);
	}
}