<?php

namespace iBrand\Express\Contracts;

interface GatewayInterface
{
	public function query(ExpressNumberInterface $expressNumber, array $config = []);
}