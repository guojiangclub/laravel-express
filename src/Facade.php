<?php

namespace iBrand\Express;

class Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return Express::class;
	}
}