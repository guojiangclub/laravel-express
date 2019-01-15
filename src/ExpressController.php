<?php

namespace iBrand\Express;

use Illuminate\Routing\Controller;
use Express;

class ExpressController extends Controller
{
	public function query()
	{
		$number = request('no');

		$result = Express::query($number);
		if (!empty($result) && is_array($result)) {
			return response()->json($result);
		}

		return response()->json(['success' => false, 'message' => '快递信息查询失败']);
	}
}