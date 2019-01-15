<?php

namespace iBrand\Express\Numbers;

use iBrand\Express\Contracts\ExpressNumberInterface;

abstract class BaseNumber implements ExpressNumberInterface
{
	public $number;

	public function __construct($number)
	{
		$this->number = $number;
	}

	public function getNumber()
	{
		return $this->number;
	}

	abstract public function getComCode();
}